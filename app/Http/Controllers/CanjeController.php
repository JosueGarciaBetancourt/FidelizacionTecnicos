<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\CanjeRecompensa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SolicitudesCanje;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use App\Models\CanjeRecompensaView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\RecompensaController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CanjeRecompensaController;
use App\Http\Controllers\VentaIntermediadaController;
use Exception;

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

        return view('dashboard.registrarCanjes', compact('nuevoIdCanje', 'optionsNumComprobante', 'recompensas'));
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
    
        // Actualizar la venta intermediada
        $venta->update(['apareceEnSolicitud' => 0]);

        $nuevosPuntosActuales = $validatedData['puntosRestantes_Canje'];
        VentaIntermediadaController::returnUpdatedIDEstadoVenta($venta->idVentaIntermediada, $nuevosPuntosActuales);
        
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
        /* $allCanjes = Canje::all();
        // Decodificar y extraer los valores
        $recompensasTipos = $allCanjes->map(function ($canje) {
            $recompensas = json_decode($canje->recompensasJSON, true);
            return collect($recompensas)->pluck('nombre_TipoRecompensa');
        });

        dd($recompensasTipos);  */   
        return view('dashboard.historialCanjes');
    }

    public function returnArrayHistorialCanjesTabla() {
        // Obtener las ventas intermediadas con los datos relacionados
        $canjes = Canje::query()
                    ->join('VentasIntermediadas', 'Canjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                    ->join('Tecnicos', 'VentasIntermediadas.idTecnico', '=', 'Tecnicos.idTecnico')
                    ->select(['Canjes.idCanje', 
                            'Canjes.idVentaIntermediada', 
                            'Canjes.fechaHoraEmision_VentaIntermediada', 
                            'Canjes.fechaHora_Canje', 
                            'Canjes.diasTranscurridos_Canje', 
                            'Canjes.puntosComprobante_Canje', // Puntos generados
                            'Canjes.puntosCanjeados_Canje', 
                            'Canjes.puntosRestantes_Canje', 
                            'Tecnicos.idTecnico', 
                            'Tecnicos.nombreTecnico'])
                    ->get();

        $index = 1;
    
        // Mapear las ventas para estructurarlas
        $data = $canjes->map(function ($canje) use (&$index) {
            return [
                'index' => $index++,
                'idCanje' => $canje->idCanje,
                'fechaHora_Canje' => $canje->fechaHora_Canje,
                'idVentaIntermediada' => $canje->idVentaIntermediada,
                'puntosComprobante_Canje' => $canje->puntosComprobante_Canje,
                'fechaHoraEmision_VentaIntermediada' => $canje->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_Canje' => $canje->diasTranscurridos_Canje,
                'idTecnico' => $canje->idTecnico,
                'nombreTecnico' => $canje->nombreTecnico,
                'puntosCanjeados_Canje' => $canje->puntosCanjeados_Canje,
                'puntosRestantes_Canje' => $canje->puntosRestantes_Canje,

                // Campos compuestos
                'idVentaIntermediada_puntosGenerados' => $canje->idVentaIntermediada . " " . $canje->puntosComprobante_Canje,
                'nombreTecnico_idTecnico' => $canje->nombreTecnico . " DNI: " . $canje->idTecnico,
            ];
        });
    
        return $data->toArray();
    }

    public function tablaHistorialCanje(Request $request) {
        if ($request->ajax()) {
            $canjes = $this->returnArrayHistorialCanjesTabla();
                  
            if (empty($canjes)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No hay canjes registrados aún.'
                ], 204);
            }
    
            return DataTables::of($canjes)
                ->addColumn('actions', 'tables.historialCanjeActionColumn')
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        return abort(403);
    }

    public function getObjCanjeAndDetailsByIdCanje($idCanje) {
        try {
            // Obtener el objeto canje
            $objCanje = Canje::findOrFail($idCanje);

            // Consulta a la vista
            $detalles = DB::table('canje_recompensas_view')
                            ->where('idCanje', $idCanje)
                            ->get();
    
            // Controller::printJSON($detalles);

            // Verificar si se encontraron resultados
            if ($detalles->isEmpty()) {
                return response()->json(['message' => 'No se encontraron canjes para el ID proporcionado'], 404);
            }
    
            // Mapear los resultados para agregar el índice incremental
            $detallesCanjes = $detalles->map(function ($item, $key) {
                $item->index = $key + 1; // Asignar índice a cada elemento
                return $item;
            });
    
            return response()->json([
                'objCanje' => $objCanje,
                'detallesCanjes' => $detallesCanjes
            ]);
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al obtener los detalles del canje ' . $idCanje, 'details' => $e->getMessage()], 500);
        }
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

    public function returnArrayHistorialCanjesTablaPDF() {
        try {
            $canjes = Canje::query()
                ->join('VentasIntermediadas', 'Canjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                ->select('Canjes.*', 'VentasIntermediadas.idTecnico', 'VentasIntermediadas.nombreTecnico')
                ->get();

            //Controller::printJSON($canjes);
            //Controller::printJSON(json_decode($canjes->pluck('recompensasJSON'), true));
            
            /* Ejemplo de canje 
                "idCanje": "CANJ-00001",
                "idVentaIntermediada": "F001-00000072",
                "fechaHoraEmision_VentaIntermediada": "2024-11-08 10:00:00",
                "fechaHora_Canje": "2024-11-09 10:00:00",
                "diasTranscurridos_Canje": 1,
                "puntosComprobante_Canje": 75,
                "puntosCanjeados_Canje": 75,
                "puntosRestantes_Canje": 0,
                "comentario_Canje": "Ejemplo de comentario de canje",
                "idUser": 2,
                "created_at": "2025-01-21T18:18:40.000000Z",
                "updated_at": "2025-01-21T18:18:40.000000Z",
                "idTecnico": "77043114",
                "nombreTecnico": "Josu\u00e9 Garc\u00eda Betancourt",
                "recompensasJSON": [
                    {
                        "idCanje": "CANJ-00001",
                        "idRecompensa": "RECOM-002",
                        "idTipoRecompensa": 3,
                        "nombre_TipoRecompensa": "EPP",
                        "descripcionRecompensa": "Par de rodilleras para cer\u00e1mica",
                        "cantidad": 1,
                        "costoRecompensa": 35,
                        "puntosTotales": 35,
                        "canjeRecompensa_created_at": "2025-01-21 13:18:41"
                    },
                    {
                        "idCanje": "CANJ-00001",
                        "idRecompensa": "RECOM-004",
                        "idTipoRecompensa": 4,
                        "nombre_TipoRecompensa": "Herramienta",
                        "descripcionRecompensa": "Juego de destornilladores",
                        "cantidad": 1,
                        "costoRecompensa": 40,
                        "puntosTotales": 40,
                        "canjeRecompensa_created_at": "2025-01-21 13:18:41"
                    }
                ]
            */   
                
            $index = 1;
        
            $data = $canjes->map(function ($canje) use (&$index) {
                return [
                    'index' => $index++,
                    'idCanje' => $canje->idCanje,
                    'fechaHora_Canje' => $canje->fechaHora_Canje,
                    'idVentaIntermediada' => $canje->idVentaIntermediada,
                    'puntosComprobante_Canje' => $canje->puntosComprobante_Canje,
                    'fechaHoraEmision_VentaIntermediada' => $canje->fechaHoraEmision_VentaIntermediada,
                    'diasTranscurridos_Canje' => $canje->diasTranscurridos_Canje,
                    'idTecnico' => $canje->idTecnico,
                    'nombreTecnico' => $canje->nombreTecnico,
                    'puntosCanjeados_Canje' => $canje->puntosCanjeados_Canje,
                    'puntosRestantes_Canje' => $canje->puntosRestantes_Canje,
                    'recompensasJSON' => json_decode($canje['recompensasJSON'], true),
                ];
            });
            
            /* Log::info("DATA CANJES PDF:");
            Controller::printJSON($data); */
            return $data->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function exportarAllCanjesPDF()
    {
        try {
            // Cargar datos de técnicos con oficios
            $data = $this->returnArrayHistorialCanjesTablaPDF();
            
            /* Log::info("DATA exportarAllCanjesPDF:");
            Controller::printJSON($data); */

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de canjes.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4'; // Tamaño del papel
            $view = 'tables.tablaCanjesPDFA4'; // Vista para generar el PDF
            $fileName = "Club de técnicos DIMACOF-Listado de Canjes-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])->setPaper($paperSize, 'landscape'); // Configurar tamaño y orientación

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            /* Log::error('Error en exportarAllCanjesPDF', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF de Canjes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
