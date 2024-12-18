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
use App\Models\SolicitudesCanje;

class CanjeController extends Controller
{
    public static function generarIdCanje()
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

    public function registrar()
    {
        $tecnicos = Tecnico::all();
        // Obtener las ventas intermediadas no asociadas con alguna solicitud de canje
        $ventas = VentaIntermediada::with('estadoVenta') // Cargar relación de estado
                                    ->doesntHave('solicitudesCanje') // Filtrar comprobantes sin solicitudes de canje
                                    ->whereIn('idEstadoVenta', [1, 2]) // Estados: En espera o Redimido (parcial)
                                    ->get();

        // Obtener todas las recompensas activas (Inicialmente "RECOM-000, Efectivo" está inactivo)
        $recompensas = Recompensa::query()
                                ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                                ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa']) // Selecciona campos relevantes
                                ->whereNull('Recompensas.deleted_at')
                                ->orderBy('Recompensas.idRecompensa', 'ASC') 
                                ->get();
        // Obtener las opciones de número de comprobante
        $optionsNumComprobante = [];
        foreach ($ventas as $venta) {
            $optionsNumComprobante[] = $venta->idVentaIntermediada;
        }
        
        // Nuevo Id Canje 
        $nuevoIdCanje = CanjeController::generarIdCanje();
        return view('dashboard.registrarCanjes', compact('nuevoIdCanje', 'tecnicos', 'optionsNumComprobante', 'recompensas'));
    }

    public function store(Request $request) {
        try {
            // Comenzar una transacción
            DB::beginTransaction();
    
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'idVentaIntermediada' => 'required|exists:VentasIntermediadas,idVentaIntermediada',
                'puntosComprobante_Canje' => 'required|numeric|min:0',
                'puntosCanjeados_Canje' => 'required|numeric|min:0',
                'puntosRestantes_Canje' => 'required|numeric|min:0',
                'recompensas_Canje' => 'required',
                'comentario_Canje' => 'nullable|string',
            ]);

            // Crear el canje usando la función separada
            CanjeController::crearCanje($validatedData);
    
            // Confirmar la transacción si todo sale bien
            DB::commit();
    
            // Redirigir con éxito
            return redirect()->route('canjes.registrar')->with('successCanjeStore', 'Canje guardado correctamente.');
        } catch (ValidationException $e) {
            // Revertir la transacción si ocurre un error de validación
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Revertir la transacción si ocurre cualquier otra excepción
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar el canje: ' . $e->getMessage()])->withInput();
        }
    }
    
    public static function crearCanje(array $validatedData) {
        // Generar ID para el canje
        $idCanje = CanjeController::generarIdCanje();
    
        // Obtener la venta intermediada y calcular días transcurridos
        $venta = VentaIntermediada::findOrFail($validatedData['idVentaIntermediada']);
        $fechaHoraEmision = $venta->fechaHoraEmision_VentaIntermediada;
        $diasTranscurridos = Controller::returnDiasTranscurridosHastaHoy($fechaHoraEmision);
    
        // Obtener ID del usuario autenticado
        $idUser = Auth::id();
        $recompensasJson = $validatedData['recompensas_Canje'];
        
        // Crear el nuevo canje
        $canje = Canje::create([
            'idCanje' => $idCanje,
            'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
            'fechaHoraEmision_VentaIntermediada' => $fechaHoraEmision,
            'diasTranscurridos_Canje' => $diasTranscurridos,
            'puntosComprobante_Canje' => $validatedData['puntosComprobante_Canje'],
            'puntosCanjeados_Canje' => $validatedData['puntosCanjeados_Canje'],
            'puntosRestantes_Canje' => $validatedData['puntosRestantes_Canje'],
            'comentario_Canje'=> $validatedData['comentario_Canje'],
            'idUser' => $idUser,
        ]);
    
        // Actualizar puntos en la venta intermediada
        $nuevosPuntosActuales = $validatedData['puntosRestantes_Canje'];
        VentaIntermediadaController::updateStateVentaIntermediada($venta->idVentaIntermediada, $nuevosPuntosActuales);
    
        // Actualizar el stock de las recompensas
        $recompensasCanje = json_decode($recompensasJson);
        foreach ($recompensasCanje as $recom) {
            if ($recom->idRecompensa != "RECOM-000") {
                RecompensaController::updateStockByIdRecompensaCantidad($recom->idRecompensa, $recom->cantidad);
            }
        }
    
        // Registrar en la tabla CanjesRecompensas
        foreach ($recompensasCanje as $recom) {
            CanjeRecompensaController::updateCanjeRecompensa($idCanje, $recom->idRecompensa, $recom->cantidad);
        }
    
        // Actualizar puntos actuales del técnico
        TecnicoController::updatePuntosActualesTecnicoById($venta['idTecnico']);
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
            return response()->json(['error' => 'Error al obtener los detalles del canje ' . $idCanje, 'details' => $e->getMessage()], 500);
        }
    }

    public function getDetalleSolicitudesCanjesRecompensasByIdSolicitudCanje($idSolicitudCanje) {
        try {
            // Consulta a la vista
            $resultados = DB::table('solicitudCanje_recompensas_view')
                            ->where('idSolicitudCanje', $idSolicitudCanje)
                            ->get();
            // Verificar si se encontraron resultados
            if ($resultados->isEmpty()) {
                return response()->json(['message' => 'No se encontraron solicitudes canje para el ID proporcionado: ' . $idSolicitudCanje], 404);
            }
    
            // Mapear los resultados para agregar el índice incremental
            $solicitudesCanjesRecompensasAll = $resultados->map(function ($item, $key) {
                $item->index = $key + 1; // Asignar índice a cada elemento
                return $item;
            });
    
            return response()->json($solicitudesCanjesRecompensasAll);
    
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al obtener los detalles de la solicitud canje ' . $idSolicitudCanje, 'details' => $e->getMessage()], 500);
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

    public function solicitudesApp()
    {
        // Obtiene las solicitudes de canje con las relaciones necesarias
        $solicitudesCanje = SolicitudesCanje::with([
            'tecnicos',                       // Relación con Técnico
            'estadosSolicitudCanje',          // Relación con el estado de la solicitud
            'ventaIntermediada',              // Relación con la venta intermediada
            'solicitudCanjeRecompensa.recompensas', // Relación con las recompensas
        ])->get();

        // Renderiza la vista con los datos
        return view('dashboard.solicitudesAppCanjes', compact('solicitudesCanje'));
    }

    public function getCanjeDataPDFByIdCanje($idCanje) {
        try {
            $canjeWithTecnico = Canje::query()
                ->join('VentasIntermediadas', 'Canjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                ->join('Tecnicos', 'VentasIntermediadas.idTecnico', '=', 'Tecnicos.idTecnico')
                ->select(['Canjes.*', 'Tecnicos.*'])
                ->where('idCanje', $idCanje)
                ->first();
                
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
        try {
            $data = $this->getCanjeDataPDFByIdCanje($idCanje);
          
            if (empty($data)) {
                throw new \Exception("Datos no encontrados para canje con código: {$idCanje}");
            }
            
            $canjeWithTecnico = $data['canjeWithTecnico'];
            $canjesRecompensas = $data['canjesRecompensas'];
            $totalPuntos = $canjesRecompensas->sum('puntosTotales');
    
            // Definir tamaño de papel según $size
            $paperSize = match ($size) {
                'A4' => 'A4',
                '80mm' => [0, 0, 221, 776], 
                '50mm' => [0, 0, 128, 776], 
                default => 'A4',
            };

            $view = match ($size) {
                'A4' => 'dashboard.canjePDFA4',
                '80mm' => 'dashboard.canjePDF80mm',
                '50mm' => 'dashboard.canjePDF50mm',
                default => 'A4',    
            };
    
            // Generar el nombre del archivo
            $fileName = "Club de técnicos_{$idCanje}_{$size}.pdf";

            // Generar el PDF
            $pdf = Pdf::setPaper($paperSize)
                        ->loadView($view, compact('canjeWithTecnico', 'canjesRecompensas', 'totalPuntos', 'size'));
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    
}
