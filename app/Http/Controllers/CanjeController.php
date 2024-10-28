<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TecnicoController;
use Illuminate\Validation\ValidationException;

class CanjeController extends Controller
{
    public function generarIdCanje()
    {
        // Obtener el último valor de idCanje
        $ultimoCanje = Canje::orderByDesc('idCanje')->first();

        // Extraer el número del último idCanje
        $ultimoNumero = $ultimoCanje ? intval(substr($ultimoCanje->idCanje, 5)) : 0;

        // Incrementar el número para generar el siguiente idCanje
        $nuevoNumero = $ultimoNumero + 1;

        // Formatear el nuevo número con ceros a la izquierda
        $nuevoIdCanje = 'CANJ-' . str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);

        return $nuevoIdCanje;
    }

    public function create()
    {
        $tecnicos = Tecnico::all();
        $ventas = VentaIntermediada::all();
        // Obtener todas las recompensas en una sola consulta
        $recompensas = Recompensa::all();

        // Obtener la primera recompensa
        $recomEfectivo = $recompensas->first();
        // Obtener el resto de las recompensas excluyendo la primera
        $RecompensasWithoutEfectivo = $recompensas->slice(1);
        
        // Obtener las opciones de número de comprobante
        $optionsNumComprobante = [];
        foreach ($ventas as $venta) {
            $optionsNumComprobante[] = $venta->idVentaIntermediada;
        }
        
        return view('dashboard.canjes', compact('tecnicos', 'ventas', 'optionsNumComprobante', 
                                                'RecompensasWithoutEfectivo', 'recomEfectivo'));
    }

    public function store(Request $request) {
        try {
            // Comenzar una transacción
            DB::beginTransaction();
            
            //dd($request->all());

            // Validar los datos de entrada
            $validatedData = $request->validate([
                'idVentaIntermediada' => 'required|exists:VentasIntermediadas,idVentaIntermediada',
                'puntosComprobante_Canje' => 'required|numeric|min:0',
                'puntosCanjeados_Canje' => 'required|numeric|min:0',
                'puntosRestantes_Canje' => 'required|numeric|min:0',
            ]);

            $idCanje = $this->generarIdCanje();
            // Obtener el objeto VentaIntermediada completo
            $venta = VentaIntermediada::findOrFail($validatedData['idVentaIntermediada']);
            $fechaHoraEmision = $venta->fechaHoraEmision_VentaIntermediada;
            // Calcular los días transcurridos
            $diasTranscurridos = $this->returnDiasTranscurridosHastaHoy($fechaHoraEmision);
            $idUser = Auth::id(); // Obtiene el id del usuario autenticado

            /*  ===========================MIGRACIÓN DE LA TABLA CANJES===========================
                $table->string('idCanje', 10); //CANJ-00001 (se genera automáticamente)
                $table->string('idVentaIntermediada', 13);
                $table->dateTime('fechaHoraEmision_VentaIntermediada');
                $table->dateTime('fechaHora_Canje')->useCurrent();
                $table->integer('diasTranscurridos_Canje')->unsigned(); 
                $table->integer('puntosComprobante_Canje')->unsigned();
                $table->integer('puntosCanjeados_Canje')->unsigned();
                $table->integer('puntosRestantes_Canje')->unsigned(); 
                $table->string('[(idRecompensa, cantidad), ]')->nullable();
                $table->unsignedBigInteger('idUser');
            */

            // Crear el nuevo canje
            $canje = Canje::create([
                'idCanje' => $idCanje,
                'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
                'fechaHoraEmision_VentaIntermediada' => $fechaHoraEmision,
                'diasTranscurridos_Canje' => $diasTranscurridos,
                'puntosComprobante_Canje' => $validatedData['puntosComprobante_Canje'],
                'puntosCanjeados_Canje' => $validatedData['puntosCanjeados_Canje'],
                'puntosRestantes_Canje' => $validatedData['puntosRestantes_Canje'],
                'idUser' => $idUser,
            ]);

            //dd($canje);

            // Actualizar los campos en la venta intermediada
            $venta->update([
                'puntosActuales_VentaIntermediada' => $validatedData['puntosRestantes_Canje'],
                'estadoVentaIntermediada' => "Redimido",
            ]);

            // Si todo sale bien, confirmar la transacción
            DB::commit();

            // Actualizar los puntos actuales del técnico
            TecnicoController::updatePuntosActualesTecnicoById($venta['idTecnico']); // Llamado estático

            // Redirigir con éxito
            return redirect()->route('canjes.create')->with('successCanjeStore', 'Canje guardado correctamente.');

        } catch (ValidationException $e) {
            // Revertir la transacción si ocurre un error de validación
            DB::rollBack();
            dd($e);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Revertir la transacción si ocurre cualquier otra excepción
            DB::rollBack();
            dd($e);
            return back()->withErrors(['error' => 'Error al procesar el dosaje o la predicción: ' . $e->getMessage()])->withInput();
        }
    }
}
