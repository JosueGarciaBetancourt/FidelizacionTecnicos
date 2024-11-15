<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\CanjeRecompensa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use App\Models\CanjeRecompensaView;
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
            return back()->withErrors(['error' => 'Error al procesar el canje: ' . $e->getMessage()])->withInput();
        }
    }

    public function historial() {
        $allCanjes = Canje::query()
                ->join('VentasIntermediadas', 'Canjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                ->join('Tecnicos', 'VentasIntermediadas.idTecnico', '=', 'Tecnicos.idTecnico')
                ->select(['Canjes.*', 'Tecnicos.*'])
                ->get();
        return view('dashboard.historialCanjes', compact('allCanjes'));
    }

    /*public function getDetalleCanjesRecompensasByIdCanje($idCanje) {
        // Hacer la consulta a la BD
        $resultados = CanjeRecompensa::query()
                        ->join('Canjes', 'CanjesRecompensas.idCanje', '=', 'Canjes.idCanje')
                        ->join('Recompensas', 'CanjesRecompensas.idRecompensa', '=', 'Recompensas.idRecompensa')
                        ->where('CanjesRecompensas.idCanje', $idCanje)
                        ->select([
                            'CanjesRecompensas.idCanje',
                            'CanjesRecompensas.idRecompensa',
                            'CanjesRecompensas.cantidad',
                            'CanjesRecompensas.costoRecompensa',
                            'CanjesRecompensas.created_at as canjeRecompensa_created_at', // Alias para evitar conflictos
                            'Recompensas.tipoRecompensa',
                            'Recompensas.descripcionRecompensa'
                        ])
                        ->get();
    
        // Transformar los datos
        $contador = 0; // Definir fuera del closure
    
        $canjesRecompensasAll = $resultados->map(function ($resultado) use (&$contador) { // Pasar $contador como referencia
            return [
                'idCanje' => $resultado->idCanje,
                'index' => $contador++, // Incrementar después de usar el valor actual
                'idRecompensa' => $resultado->idRecompensa,
                'tipoRecompensa' => $resultado->tipoRecompensa,
                'descripcionRecompensa' => $resultado->descripcionRecompensa,
                'cantidad' => $resultado->cantidad,
                'costoRecompensa' => $resultado->costoRecompensa,
                'puntosTotales' => $resultado->cantidad * $resultado->costoRecompensa,
                //'created_at' => $resultado->canjeRecompensa_created_at, // Comentar si no es necesario
            ];
        })->toArray();
    
        return response()->json($canjesRecompensasAll);
    }*/

    public function getDetalleCanjesRecompensasByIdCanje($idCanje) {
        try {
            // Consulta a la vista
            $resultados = DB::table('canje_recompensas_view')
                            ->where('idCanje', $idCanje)
                            ->get();
    
            // Verificar si se encontraron resultados
            if ($resultados->isEmpty()) {
                return response()->json(['message' => 'No se encontraron canjes para el ID proporcionado'], 404);
            }
    
            // Mapear los resultados para agregar el índice incremental
            $canjesRecompensasAll = $resultados->map(function ($item, $key) {
                $item->index = $key + 1; // Asignar índice a cada elemento
                return $item;
            });
    
            return response()->json($canjesRecompensasAll);
    
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al obtener los detalles del canje', 'details' => $e->getMessage()], 500);
        }
    }

    /*
    public function prediction()
    {
        $apoderadoId = Auth::id();

        // Iniciar el temporizador
        $startTime = microtime(true);
        
        $resultados = Dosaje::query()
            ->join('Hijos', 'Dosajes.idHijo', '=', 'Hijos.idHijo')
            ->join('Doctores', 'Dosajes.idDoctor', '=', 'Doctores.idDoctor')
            ->join('Establecimientos', 'Dosajes.idEstablecimiento', '=', 'Establecimientos.idEstablecimiento')
            ->join('Distritos', 'Establecimientos.idDistrito', '=', 'Distritos.idDistrito')
            ->join('MicroRedes', 'Distritos.idMicroRed', '=', 'MicroRedes.idMicroRed')
            ->where('Hijos.idApoderado', $apoderadoId)
            ->select([
                'Dosajes.*',
                 //'Dosajes.created_at as dosajeCreatedAt',
                'Hijos.*',
                'Doctores.*',
                'Establecimientos.*',
                'Distritos.*',
                'MicroRedes.*'
            ])
            ->get();

        // Transformar los resultados en un array simple
        $datosSimplificados = $resultados->map(function ($resultado) {
            return [
                // Dosajes
                'idDosaje' => $resultado->idDosaje,
                //'created_at' => $resultado->dosajeCreatedAt,
                'fecha_Dosaje' => $resultado->fecha_Dosaje,
                'valorHemoglobina_Dosaje' => $resultado->valorHemoglobina_Dosaje,
                'nivelAnemia_Dosaje' => $resultado->nivelAnemia_Dosaje,
                'peso_Dosaje' => $resultado->peso_Dosaje,
                'talla_Dosaje' => $resultado->talla_Dosaje,
                'edadMeses_Dosaje' => $resultado->edadMeses_Dosaje,
                'nivelHierro_Dosaje' => $resultado->nivelHierro_Dosaje,
                'estadoRecuperacion_Dosaje' => $resultado->estadoRecuperacion_Dosaje,
                'fechaRecuperacionReal' => $resultado->fechaRecuperacionReal,
                // Hijos
                'idHijo' => $resultado->idHijo,
                'nombre_Hijo' => $resultado->nombre_Hijo,
                'apellido_Hijo' => $resultado->apellido_Hijo,
                'fechaNacimiento_Hijo' => $resultado->fechaNacimiento_Hijo,
                'sexo_Hijo' => $resultado->sexo_Hijo,
                'nombreSeguro_Hijo' => $resultado->nombreSeguro_Hijo,
                // Doctores
                'idDoctor' => $resultado->idDoctor,
                'nombre_Doctor' => $resultado->nombre_Doctor,
                'apellido_Doctor' => $resultado->apellido_Doctor,
                'celular_Doctor' => $resultado->celular_Doctor,
                // Establecimientos
                'idEstablecimiento' => $resultado->idEstablecimiento,
                'nombreEstablecimiento' => $resultado->nombreEstablecimiento,
                'nombreDistrito' => $resultado->nombreDistrito,
                // Distritos
                'idDistrito' => $resultado->idDistrito,
                'nombreDistrito' => $resultado->nombreDistrito,
                // MicroRedes
                'idMicroRed' => $resultado->idMicroRed,
                'nombreMicroRed' => $resultado->nombreMicroRed,
            ];
        })->toArray();

        // Calcular el tiempo de ejecución
        $executionTime = microtime(true) - $startTime;
        
        Log::info("Tiempo de ejecución de la consulta usando ELOCUENT ORM: {$executionTime} segundos");
        
        dd($datosSimplificados);

        return view('apoderados.apoderadosPrediction', ['resultados' => $datosSimplificados]);
    }
    */

    public function solicitudesApp() {
        return view('dashboard.solicitudesAppCanjes');
    }

    public function getCanjeDataPDFByIdCanje($idCanje) {
        try {
            $canjeWithTecnico = Canje::query()
                ->join('VentasIntermediadas', 'Canjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                ->join('Tecnicos', 'VentasIntermediadas.idTecnico', '=', 'Tecnicos.idTecnico')
                ->select(['Canjes.*', 'Tecnicos.*'])
                ->where('idCanje', $idCanje)
                ->first();
                
            $canjeWithTecnico->fechaHoraEmision = now()->toDateTimeString();
    
            $canjesRecompensas = DB::table('canje_recompensas_view')
                ->where('idCanje', $idCanje)
                ->get();
            
            // Retornar un array asociativo con ambos valores
            return [
                'canjeWithTecnico' => $canjeWithTecnico,
                'canjesRecompensas' => $canjesRecompensas
            ];
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los detalles del canje para generar el PDF', 
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function canjePDF($size, $idCanje) {
        // Obtener los datos
        $data = $this->getCanjeDataPDFByIdCanje($idCanje);
        
        $canjeWithTecnico = $data['canjeWithTecnico'];
        $canjesRecompensas = $data['canjesRecompensas'];

        /*
            0 => {#1104 ▼
            +"idCanje": "CANJ-00001"
            +"idRecompensa": "RECOM-002"
            +"tipoRecompensa": "EPP"
            +"descripcionRecompensa": "Par de rodilleras para cerámica"
            +"cantidad": 1
            +"costoRecompensa": 35.0
            +"puntosTotales": 35.0
            +"canjeRecompensa_created_at": "2024-11-15 00:36:23"
            }
            1 => {#1321 ▼
            +"idCanje": "CANJ-00001"
            +"idRecompensa": "RECOM-004"
            +"tipoRecompensa": "Herramienta"
            +"descripcionRecompensa": "Juego de destornilladores"
            +"cantidad": 1
            +"costoRecompensa": 40.0
            +"puntosTotales": 40.0
            +"canjeRecompensa_created_at": "2024-11-15 00:36:23"
            }
        */
        
        // Generar el PDF
        $pdf = Pdf::loadView('dashboard.canjePDF', compact('canjeWithTecnico', 'canjesRecompensas', 'size'));
        return $pdf->stream();
    }
}
