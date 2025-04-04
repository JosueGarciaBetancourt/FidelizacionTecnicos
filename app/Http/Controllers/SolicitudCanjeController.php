<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SolicitudesCanje;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use App\Models\TecnicoNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\SolicitudCanjeRecompensa;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\SystemNotificationController;
use Database\Seeders\VentaIntermediadaSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SolicitudCanjeController extends Controller
{
    public static function updateEstadosSolicitudCanjeMaxDayCanje() {
        $solicitudesFiltered = SolicitudesCanje::with(['ventasIntermediadas' => function($query) {
                $query->select('idVentaIntermediada', 'idEstadoVenta', 'fechaHoraEmision_VentaIntermediada');
            }])
            ->select(['idSolicitudCanje', 'idEstadoSolicitudCanje', 'idVentaIntermediada']) // Agrega 'idVentaIntermediada' para enlazar con la relación
            ->get()
            ->filter(function ($solicitud) { 
                return in_array($solicitud->idEstadoSolicitudCanje, [1/* , 4 */]) && in_array($solicitud->ventasIntermediadas->idEstadoVenta, [4]);
                // Descomentar y reemplazar la segunda condición con !in_array($solicitud->ventasIntermediadas->idEstadoVenta, [3])
                // para probar como regresa a su estado anterior al cambiar fechaHoraEmision_VentaIntermediada en la BD
        });

        //dd($solicitudesFiltered->pluck("idEstadoSolicitudCanje", "idSolicitudCanje"));
        //dd($solicitudesFiltered);
        //dd($solicitudesFiltered->pluck("ventasIntermediadas.idEstadoVenta"));

        if ($solicitudesFiltered) {
            foreach ($solicitudesFiltered as $soli) {
                $newIdEstadoSolicitudCanje = ($soli->ventasIntermediadas->idEstadoVenta == 4) ? 4 : 1;
                $soli->update([
                    'fechaHoraEmision_VentaIntermediada' => $soli->ventasIntermediadas->fechaHoraEmision_VentaIntermediada,
                    'idEstadoSolicitudCanje' => $newIdEstadoSolicitudCanje
                ]);
            }
        }
    }

    public function create()
    {
        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();

        return view('dashboard.solicitudesAppCanjes', compact('notifications'));
    }

    public function getObjSolicitudCanjeAndDetailsByIdSolicitudCanje($idSolicitudCanje) {
        try {
            // Obtener el objeto canje
            $objSolicitudCanje = SolicitudesCanje::findOrFail($idSolicitudCanje);
            
            // Consulta a la vista
            $detalles = DB::table('solicitudCanje_recompensas_view')
                            ->where('idSolicitudCanje', $idSolicitudCanje)
                            ->get();

            // Verificar si se encontraron resultados
            if ($detalles->isEmpty()) {
                return response()->json(['message' => 'No se encontraron solicitudes de canjes para el ID proporcionado: ' . $idSolicitudCanje], 404);
            }
    
            // Mapear los resultados para agregar el índice incremental
            $detallesSolicitudesCanjes = $detalles->map(function ($item, $key) {
                $item->index = $key + 1; // Asignar índice a cada elemento
                return $item;
            });
            
            // Controller::printJSON($objSolicitudCanje);

            return response()->json([
                'objSolicitudCanje' => $objSolicitudCanje,
                'detallesSolicitudesCanjes' => $detallesSolicitudesCanjes
            ]);
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al obtener los detalles de la solicitud canje ' . $idSolicitudCanje, 'details' => $e->getMessage()], 500);
        }
    }
    
    public function crearSolicitud(Request $request)
    {   
        $validatedData = $request->validate([
            'idVentaIntermediada' => 'required|string|exists:VentasIntermediadas,idVentaIntermediada',
            'idTecnico' => 'required|string|exists:Tecnicos,idTecnico',
            'recompensas' => 'required|array',
            'recompensas.*.idRecompensa' => 'required|string|exists:Recompensas,idRecompensa',
            'recompensas.*.cantidad' => 'required|integer|min:1',
            'recompensas.*.costoRecompensa' => 'required|numeric|min:0',
            'puntosCanjeados_SolicitudCanje' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Obtener la venta solicitada
            $venta = VentaIntermediada::where('idVentaIntermediada', $validatedData['idVentaIntermediada'])->firstOrFail();
            $nombreTecnico = Tecnico::find($validatedData['idTecnico'])->value('nombreTecnico');

            // Convertir fechaHoraEmision_VentaIntermediada a Carbon
            $fechaEmision = \Carbon\Carbon::parse($venta->fechaHoraEmision_VentaIntermediada);

            // Calcular los valores de los nuevos campos
            $fechaHoraSolicitud = now(); // Fecha actual
            $diasTranscurridos = $fechaEmision->diffInDays($fechaHoraSolicitud);
            $puntosComprobante = $venta->puntosGanados_VentaIntermediada;
            $puntosActuales = $venta->puntosActuales_VentaIntermediada;
            $puntosCanjeados = $validatedData['puntosCanjeados_SolicitudCanje'];
            $puntosRestantes = $puntosActuales - $puntosCanjeados;

            if ($puntosRestantes < 0) {
                return response()->json([
                    'message' => 'Los puntos canjeados no pueden exceder los puntos del comprobante.',
                ], 422);
            }

            // Validación de stock de recompensas
            foreach ($validatedData['recompensas'] as $recompensa) {
                $recompensaData = Recompensa::find($recompensa['idRecompensa']);
                
                // Comprobar si el stock es suficiente
                if ($recompensaData->stock_Recompensa < $recompensa['cantidad']) {
                    return response()->json([
                        'message' => 'No hay suficiente stock para la recompensa: ' . $recompensaData->descripcionRecompensa,
                    ], 422);
                }
            }

            // Generar un ID único para la solicitud
            $idSolicitudCanje = 'SOLICANJ-' . str_pad(SolicitudesCanje::count() + 1, 5, '0', STR_PAD_LEFT);
            Cache::forget('settings_cache');

            // Crear la solicitud de canje
            $solicitud = SolicitudesCanje::create([
                'idSolicitudCanje' => $idSolicitudCanje,
                'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
                'idTecnico' => $validatedData['idTecnico'],
                'nombreTecnico' => $nombreTecnico,
                'idEstadoSolicitudCanje' => 1, // Estado por defecto: Pendiente
                'fechaHoraEmision_VentaIntermediada' => $venta->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $diasTranscurridos,
                'puntosComprobante_SolicitudCanje' => $puntosComprobante,
                'puntosActuales_SolicitudCanje' => $puntosActuales,
                'puntosCanjeados_SolicitudCanje' => $puntosCanjeados,
                'puntosRestantes_SolicitudCanje' => $puntosRestantes,
                'comentario_SolicitudCanje' => 'No registrado aún',
                'maxdayscanje_SolicitudCanje' => config('settings.maxdayscanje'),
            ]);

            // Crear los registros en la tabla de recompensas asociadas
            foreach ($validatedData['recompensas'] as $recompensa) {
                SolicitudCanjeRecompensa::create([
                    'idSolicitudCanje' => $idSolicitudCanje,
                    'idRecompensa' => $recompensa['idRecompensa'],
                    'cantidad' => $recompensa['cantidad'],
                    'costoRecompensa' => $recompensa['costoRecompensa'],
                ]);
            }

            // Modificar la venta intermediada solicitada
            $venta->update([
                'idEstadoVenta' => 5,
                'apareceEnSolicitud' => 1,
            ]);

            //Log::info($venta);

            // Crear la notificación asociada
            SystemNotification::create([
                'icon' => 'request_page',
                'title' => 'Nueva solicitud de canje',
                'tblToFilter' => 'tblSolicitudesAppCanje',
                'item' => $idSolicitudCanje,
                'description' => 'recibida desde app móvil',
                'routeToReview' => 'solicitudescanjes.create',
            ]);
            
            Controller::$newNotifications = true;

            DB::commit();

            return response()->json([
                'message' => 'Solicitud de canje creada exitosamente.',
                'data' => $solicitud,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error al crear la solicitud de canje.' . $e);
            return response()->json([
                'message' => 'Error al crear la solicitud de canje.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function eliminarSolicitud($idSolicitudCanje)
    {
        DB::beginTransaction();

        try {
            // Buscar la solicitud por ID
            $solicitud = SolicitudesCanje::findOrFail($idSolicitudCanje);

            // Validar si la solicitud fue creada hace menos de 5 minutos
            $fechaHoraCreacion = \Carbon\Carbon::parse($solicitud->created_at);
            $diferenciaMinutos = now()->diffInMinutes($fechaHoraCreacion);

            if ($diferenciaMinutos > 5) {
                return response()->json([
                    'message' => 'No se puede eliminar la solicitud. Han pasado más de 5 minutos desde su creación.',
                ], 422);
            }

            // Recuperar la venta intermediada asociada
            $venta = VentaIntermediada::where('idVentaIntermediada', $solicitud->idVentaIntermediada)->firstOrFail();

            // Restaurar el estado original de la venta intermediada
            // Verificamos si `idEstadoVenta` era 1 o 2 antes de cambiar a 5
            $estadoOriginal = ($venta->apareceEnSolicitud == 1) ? ($venta->idEstadoVenta == 5 ? 1 : $venta->idEstadoVenta) : $venta->idEstadoVenta;

            // Actualizar los campos de la venta intermediada
            $venta->update([
                'idEstadoVenta' => $estadoOriginal, // Restaurar el estado original
                'apareceEnSolicitud' => 0,         // Ya no está en solicitud
            ]);

            // Eliminar las recompensas asociadas a la solicitud
            SolicitudCanjeRecompensa::where('idSolicitudCanje', $idSolicitudCanje)->delete();

            // Eliminar la solicitud
            $solicitud->delete();

            DB::commit();

            return response()->json([
                'message' => 'Solicitud eliminada exitosamente.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Solicitud no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar la solicitud.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mostrar todas las solicitudes de canje del usuario
    public function getSolicitudesPorTecnico($idTecnico)
    {
        // Selecciona los campos necesarios de la tabla solicitudes y el nombre del estado desde la tabla relacionada
        $solicitudes = SolicitudesCanje::select(
                'SolicitudesCanjes.idSolicitudCanje',
                'SolicitudesCanjes.idVentaIntermediada',
                'SolicitudesCanjes.idEstadoSolicitudCanje',
                'EstadosSolicitudesCanjes.nombre_EstadoSolicitudCanje', // Campo relacionado
                'SolicitudesCanjes.idTecnico',
                'SolicitudesCanjes.fechaHora_SolicitudCanje',
                'SolicitudesCanjes.puntosCanjeados_SolicitudCanje'
            )
            ->join('EstadosSolicitudesCanjes', 'SolicitudesCanjes.idEstadoSolicitudCanje', '=', 'EstadosSolicitudesCanjes.idEstadoSolicitudCanje')
            ->where('SolicitudesCanjes.idTecnico', $idTecnico)
            ->orderBy('SolicitudesCanjes.fechaHora_SolicitudCanje', 'desc')
            ->get();

        return response()->json($solicitudes);
    }

    // Mostrar los detalles de una solicitud de canje
    public function getDetallesSolicitud($idSolicitudCanje)
    {
        // Cargar la solicitud con las recompensas relacionadas
        $solicitud = SolicitudesCanje::with(['solicitudCanjeRecompensa.recompensas', 'estadosSolicitudCanje'])
            ->where('idSolicitudCanje', $idSolicitudCanje)
            ->first();

        if ($solicitud) {
            // Seleccionamos solo los campos necesarios para los detalles
            $detalles = [
                'idSolicitudCanje' => $solicitud->idSolicitudCanje,
                'idVentaIntermediada' => $solicitud->idVentaIntermediada,
                'fechaHoraEmision_VentaIntermediada' => $solicitud->fechaHoraEmision_VentaIntermediada,
                'idTecnico' => $solicitud->idTecnico,
                'idUser' => $solicitud->idUser,
                'fechaHora_SolicitudCanje' => $solicitud->fechaHora_SolicitudCanje,
                'diasTranscurridos_SolicitudCanje' => $solicitud->diasTranscurridos_SolicitudCanje,
                'puntosComprobante_SolicitudCanje' => $solicitud->puntosComprobante_SolicitudCanje,
                'puntosCanjeados_SolicitudCanje' => $solicitud->puntosCanjeados_SolicitudCanje,
                'puntosRestantes_SolicitudCanje' => $solicitud->puntosRestantes_SolicitudCanje,
                'comentario_SolicitudCanje' => $solicitud->comentario_SolicitudCanje,
                'nombre_EstadoSolicitudCanje' => $solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje, // Nuevo campo con validación
                'recompensas' => $solicitud->solicitudCanjeRecompensa->map(function ($recompensa) {
                    return [
                        'idRecompensa' => $recompensa->idRecompensa,
                        'cantidad' => $recompensa->cantidad,
                        'costoRecompensa' => $recompensa->costoRecompensa,
                        'nombreRecompensa' => $recompensa->recompensas->descripcionRecompensa, // Aquí suponiendo que tienes un modelo "Recompensa" con un campo "nombre"
                    ];
                }),
            ];

            return response()->json($detalles);
        }

        return response()->json(['message' => 'Solicitud no encontrada'], 404);
    }

    public function aprobarSolicitudCanje(Request $request, $idSolicitudCanje) {
        try {
            DB::beginTransaction();
    
            $idUser = Auth::id(); 
            $comentarioSolicitudCanje = $request->input('comentario'); // Recibiendo el comentario desde el modal de confirmación de solicitud de canje
    
            $solicitudCanje = SolicitudesCanje::findOrFail($idSolicitudCanje);
            $solicitudesCanjesRecompensas = SolicitudCanjeRecompensa::where('idSolicitudCanje', $idSolicitudCanje)->get();

            $recompensas_Canje = [];
            foreach ($solicitudesCanjesRecompensas as $item) {
                $recompensas_Canje[] = [
                    'idRecompensa' => $item->idRecompensa,
                    'cantidad' => $item->cantidad,
                ];
            }
    
            $recompensasCanjeString = json_encode($recompensas_Canje);
            $comentarioDefault = "Canje creado a partir de la aprobación de una solicitud de canje desde aplicación";
    
            $data = [
                'idVentaIntermediada' => $solicitudCanje->idVentaIntermediada,
                'puntosActuales_Canje' => $solicitudCanje->puntosActuales_SolicitudCanje,
                'puntosCanjeados_Canje' => $solicitudCanje->puntosCanjeados_SolicitudCanje,
                'puntosRestantes_Canje' => $solicitudCanje->puntosRestantes_SolicitudCanje,
                'recompensas_Canje' => $recompensasCanjeString,
                'comentario_Canje' => $comentarioDefault, 
            ];
    
            CanjeController::crearCanje($data);
    
            $solicitudCanje->update([
                'idEstadoSolicitudCanje' => 2, // Aprobado
                'idUser' => $idUser,
                'comentario_SolicitudCanje' => $comentarioSolicitudCanje ?? 'Sin comentario',
            ]);
            
            // Crear notificación para el técnico
            TecnicoNotification::create([
                'idTecnico' => $solicitudCanje->idTecnico,
                'idSolicitudCanje' => $solicitudCanje->idSolicitudCanje,
                'description' => "Tu solicitud de canje con código $solicitudCanje->idSolicitudCanje fue Aprobada",
            ]);

            DB::commit();
    
            return response()->json(['message' => 'Aprobación completada', 'venta' => $solicitudCanje->idVentaIntermediada]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al aprobar la solicitud', 'details' => $e->getMessage()], 500);
        }
    }

    public function rechazarSolicitudCanje(Request $request, $idSolicitudCanje) {
        try {
             // Comenzar una transacción
             DB::beginTransaction();

             $idUser = Auth::id(); // Obtiene el id del usuario autenticado
             $comentarioSolicitudCanje = $request->input('comentario'); // Recibiendo el comentario desde el modal de confirmación de solicitud de canje
             $solicitudCanje = SolicitudesCanje::findOrFail($idSolicitudCanje);
             $ventaAsociada = VentaIntermediada::findOrFail($solicitudCanje->idVentaIntermediada);
 
             // Actualizar estado de solicitud canje
             $solicitudCanje->update([
                 'idEstadoSolicitudCanje' => 3, // Rechazado
                 'idUser' => $idUser,
                 'comentario_SolicitudCanje' => $comentarioSolicitudCanje ?? 'Sin comentario',
             ]); 
 
            // Actualizar venta intermediada
            $ventaAsociada->update(['apareceEnSolicitud' => 0]);
            VentaIntermediadaController::returnUpdatedIDEstadoVenta($ventaAsociada->idVentaIntermediada,
                                                                    $ventaAsociada->puntosActuales_VentaIntermediada);

            // Crear notificación para el técnico
            TecnicoNotification::create([
                'idTecnico' => $solicitudCanje->idTecnico,
                'idSolicitudCanje' => $solicitudCanje->idSolicitudCanje,
                'description' => "Tu solicitud de canje con código $solicitudCanje->idSolicitudCanje fue Rechazada",
            ]);

            // Si todo sale bien, confirmar la transacción
            DB::commit();
            $message = "Rechazando solicitud de canje: " . $solicitudCanje->idSolicitudCanje;
            return response()->json($message);
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte los cambios realizados antes del error
            return response()->json([
                'error' => 'Error al rechazar la solicitud de canje.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function returnArraySolicitudesCanjesTabla() {
        try {
            $solicitudescanjes = SolicitudesCanje::with(['estadosSolicitudCanje'])
                                                ->orderBy('idSolicitudCanje', 'ASC')
                                                ->get();
        
            $index = 1;
        
            // Mapear las ventas para estructurarlas
            $data = $solicitudescanjes->map(function ($solicanje) use (&$index) {
                return [
                    'index' => $index++,
                    'idSolicitudCanje' => $solicanje->idSolicitudCanje,
                    'fechaHora_SolicitudCanje' => $solicanje->fechaHora_SolicitudCanje,
                    'nombreTecnico' => $solicanje->nombreTecnico,
                    'idTecnico' => $solicanje->idTecnico,
                    'fechaHoraEmision_VentaIntermediada' => $solicanje->fechaHoraEmision_VentaIntermediada,
                    'idVentaIntermediada' => $solicanje->idVentaIntermediada,
                    'puntosComprobante_SolicitudCanje' => $solicanje->puntosComprobante_SolicitudCanje,
                    'nombre_EstadoSolicitudCanje' => $solicanje->estadosSolicitudCanje->nombre_EstadoSolicitudCanje,
                    'diasTranscurridosVenta' => $solicanje->diasTranscurridosVenta,
                    'diasTranscurridos_SolicitudCanje' => $solicanje->diasTranscurridos_SolicitudCanje,
                    'idEstadoSolicitudCanje' => $solicanje->estadosSolicitudCanje->idEstadoSolicitudCanje,

                    // Campos compuestos
                    'idVentaIntermediada_puntosGenerados' => $solicanje->idVentaIntermediada . " " . $solicanje->puntosComprobante_SolicitudCanje,
                    'nombreTecnico_idTecnico' => $solicanje->nombreTecnico . " DNI: " . $solicanje->idTecnico,
                    'diasTranscurridosVenta_diasTranscurridosSolicitud' => "Venta: " . $solicanje->diasTranscurridosVenta .
                                                                        "Solicitud: " . $solicanje->diasTranscurridos_SolicitudCanje,
                ];
            });
        
            //Controller::printJSON($data);

            return $data->toArray();
        } catch (\Exception $e) {
            Log::error('Error en returnArraySolicitudesCanjesTabla: ' . $e->getMessage());
        }
    }
    
    public function tablaSolicitudCanje(Request $request) {
        try {
            if ($request->ajax()) {
                $solicitudescanjes = $this->returnArraySolicitudesCanjesTabla();
                        
                if (empty($solicitudescanjes)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No hay solicitudes de canjes registrados aún.'
                    ], 204);
                }
            
                return DataTables::of($solicitudescanjes)
                    ->addColumn('details', 'tables.solicitudCanjeDetailsColumn')
                    ->addColumn('actions', function ($row) {
                        $idEstadoSolicitudCanje = $row['idEstadoSolicitudCanje']; 
                        $idSolicitudCanje = $row['idSolicitudCanje'];  
                        return view('tables.solicitudCanjeActionColumn', compact('row', 'idEstadoSolicitudCanje', 'idSolicitudCanje'))->render();
                    })
                    ->rawColumns(['details', 'actions'])
                    ->make(true);
            }

            return abort(403);
        } catch (\Exception $e) {
            Log::error('Error en tablaSolicitudCanje: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getObjCanjeAndDetailsByIdCanje($idCanje) {
        try {
            // Obtener el objeto canje
            $objCanje = Canje::findOrFail($idCanje);

            // Consulta a la vista
            $detalles = DB::table('canje_recompensas_view')
                            ->where('idCanje', $idCanje)
                            ->get();
    
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

    public function returnArraySolicitudesCanjesTablaPDF() {
        try {
            $solicitudesCanjes = SolicitudesCanje::query()
                ->join('VentasIntermediadas', 'SolicitudesCanjes.idVentaIntermediada', '=', 'VentasIntermediadas.idVentaIntermediada')
                ->join('EstadosSolicitudesCanjes', 'SolicitudesCanjes.idEstadoSolicitudCanje', '=', 'EstadosSolicitudesCanjes.idEstadoSolicitudCanje')
                ->select('SolicitudesCanjes.*', 'VentasIntermediadas.idTecnico', 'VentasIntermediadas.nombreTecnico',
                        'EstadosSolicitudesCanjes.nombre_EstadoSolicitudCanje')
                ->orderBy('SolicitudesCanjes.idSolicitudCanje')
                ->get();

            //Controller::printJSON($solicitudesCfechaHoraEmision_VentaIntermediadaanjes);
            //Controller::printJSON(json_decode($solicitudesCanjes->pluck('recompensasJSON'), true));
            
            $index = 1;
        
            $data = $solicitudesCanjes->map(function ($soliCan) use (&$index) {
                return [
                    'index' => $index++,
                    'idSolicitudCanje' => $soliCan->idSolicitudCanje,
                    'fechaHora_SolicitudCanje' => $soliCan->fechaHora_SolicitudCanje,
                    'fechaHoraEmision_VentaIntermediada' => $soliCan->fechaHoraEmision_VentaIntermediada,
                    'diasTranscurridos_SolicitudCanje' => $soliCan->diasTranscurridos_SolicitudCanje,
                    'idTecnico' => $soliCan->idTecnico,
                    'nombreTecnico' => $soliCan->nombreTecnico,
                    'comentario_SolicitudCanje' => $soliCan->comentario_SolicitudCanje,
                    'idVentaIntermediada' => $soliCan->idVentaIntermediada,
                    'puntosComprobante_SolicitudCanje' => $soliCan->puntosComprobante_SolicitudCanje,
                    'puntosActuales_SolicitudCanje' => $soliCan->puntosActuales_SolicitudCanje,
                    'puntosCanjeados_SolicitudCanje' => $soliCan->puntosCanjeados_SolicitudCanje,
                    'puntosRestantes_SolicitudCanje' => $soliCan->puntosRestantes_SolicitudCanje,
                    'idEstadoSolicitudCanje' => $soliCan->idEstadoSolicitudCanje,
                    'nombre_EstadoSolicitudCanje' => $soliCan->nombre_EstadoSolicitudCanje,
                    'recompensasJSON' => json_decode($soliCan['recompensasJSON'], true),
                ];
            });
            
            return $data->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    
    public function exportarAllSolicitudesCanjesPDF()
    {
        try {
            $data = $this->returnArraySolicitudesCanjesTablaPDF();
            
            // Log::info("DATA exportarAllSolicitudesCanjesPDF:");
            // Controller::printJSON($data);

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de solicitudes de canjes.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4'; // Tamaño del papel
            $view = 'tables.tablaSolicitudesCanjesPDFA4'; // Vista para generar el PDF
            $fileName = "Club de técnicos DIMACOF-Listado de Solicitudes de Canjes-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])->setPaper($paperSize, 'landscape'); // Configurar tamaño y orientación

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            /* Log::error('Error en exportarAllSolicitudesCanjesPDF', [
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

    public static function returnStateIdSolicitudCanje($idSolicitudCanje, $diasTranscurridosVenta, $maxdaysCanjeAux=null)
    {
        try {
            Cache::forget('settings_cache');
            $solicitud = SolicitudesCanje::findOrFail($idSolicitudCanje);
            $oldIdEstado = $solicitud->idEstadoSolicitudCanje;
            $maxdaysCanje = $maxdaysCanjeAux ?? config('settings.maxdaysCanje');
            
            //dd("ID: $idSolicitudCanje | Días transcurridos venta: $diasTranscurridosVenta | MaxdaysCanje: $maxdaysCanjeAux | OldIdEstado: $oldIdEstado");

            if ($diasTranscurridosVenta > $maxdaysCanje) {
                return 4; //Tiempo agotado
            } else {
                return $oldIdEstado;
            }
        } catch (ModelNotFoundException $e) {
            Log::error("Solicitud de canje no encontrado: " . $idSolicitudCanje);
            return 1; // Código de error para venta no encontrada
        } catch (\Exception $e) {
            Log::info("Probablemente no exista un estado para este solicitud de canje:" . "\n" . "Solicitud: " . $idSolicitudCanje . "\n" .
                    "Días transcurridos de venta asociada: " . $diasTranscurridosVenta);

            Log::error("Error en returnStateIdSolicitudCanje: " . $e->getMessage());
            return 1; // Código de error genérico
        }
    }
}
