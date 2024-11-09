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
use App\Http\Controllers\RecompensaController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CanjeRecompensaController;
use App\Http\Controllers\VentaIntermediadaController;

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
        
        return view('dashboard.registrarCanjes', compact('tecnicos', 'ventas', 'optionsNumComprobante', 
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
                'recompensas_Canje' => 'required',
            ]);

            $idCanje = $this->generarIdCanje();
            // Obtener el objeto VentaIntermediada completo
            $venta = VentaIntermediada::findOrFail($validatedData['idVentaIntermediada']);
            $fechaHoraEmision = $venta->fechaHoraEmision_VentaIntermediada;
            // Calcular los días transcurridos
            $diasTranscurridos = $this->returnDiasTranscurridosHastaHoy($fechaHoraEmision);
            $idUser = Auth::id(); // Obtiene el id del usuario autenticado
            $recompensasJson =  $validatedData['recompensas_Canje']; 

            // Crear el nuevo canje
            $canje = Canje::create([
                'idCanje' => $idCanje,
                'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
                'fechaHoraEmision_VentaIntermediada' => $fechaHoraEmision,
                'diasTranscurridos_Canje' => $diasTranscurridos,
                'puntosComprobante_Canje' => $validatedData['puntosComprobante_Canje'],
                'puntosCanjeados_Canje' => $validatedData['puntosCanjeados_Canje'],
                'puntosRestantes_Canje' => $validatedData['puntosRestantes_Canje'],
                'recompensas_Canje' => $recompensasJson,
                'idUser' => $idUser,
            ]);

            // Actualizar en tabla VentasIntermediadas
            $nuevosPuntosActuales = $validatedData['puntosRestantes_Canje'];
            VentaIntermediadaController::updateVentaIntermediada($venta->idVentaIntermediada, $nuevosPuntosActuales);

            // Actualizar en tabla Recompensas
            $recompensasCanje = json_decode($canje->recompensas_Canje); // Decodificar JSON a un array PHP
            foreach ($recompensasCanje as $recom) {
                RecompensaController::updateStockByIdRecompensaCantidad($recom->idRecompensa, $recom->cantidad);
            }

            // Actualizar en tabla CanjesRecompensas
            $recompensasCanje = json_decode($canje->recompensas_Canje); // Decodificar JSON a un array PHP
            foreach ($recompensasCanje as $recom) {
                CanjeRecompensaController::updateCanjeRecompensa($idCanje, $recom->idRecompensa, $recom->cantidad);
            }
            
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

    public function historial() {
        $allCanjes = Canje::all();
        return view('dashboard.historialCanjes', compact('allCanjes'));
    }

    public function solicitudesApp() {
        return view('dashboard.solicitudesAppCanjes');
    }
}
