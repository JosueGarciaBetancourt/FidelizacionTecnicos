<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SolicitudesCanje;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudCanjeRecompensa;
use App\Http\Controllers\CanjeController;
use App\Models\VentaIntermediada;
use App\Models\Recompensa;

use Illuminate\Support\Facades\Log;

class SolicitudCanjeController extends Controller
{
    public function create()
    {
        // Obtiene las solicitudes de canje con las relaciones necesarias
        $solicitudesCanje = SolicitudesCanje::with([
            'tecnicos',                       // Relación con Técnico
            'estadosSolicitudCanje',          // Relación con el estado de la solicitud
            'ventaIntermediada',              // Relación con la venta intermediada
            'solicitudCanjeRecompensa.recompensas', // Relación con las solicitudCanjeRecompensa
        ])->get();

        //dd($solicitudesCanje->pluck('nombreEstado', 'idSolicitudCanje'));
        
        return view('dashboard.solicitudesAppCanjes', compact('solicitudesCanje'));
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

            // Convertir fechaHoraEmision_VentaIntermediada a Carbon
            $fechaEmision = \Carbon\Carbon::parse($venta->fechaHoraEmision_VentaIntermediada);

            // Calcular los valores de los nuevos campos
            $fechaHoraSolicitud = now(); // Fecha actual
            $diasTranscurridos = $fechaEmision->diffInDays($fechaHoraSolicitud);
            $puntosComprobante = $venta->puntosActuales_VentaIntermediada;
            $puntosCanjeados = $validatedData['puntosCanjeados_SolicitudCanje'];
            $puntosRestantes = $puntosComprobante - $puntosCanjeados;

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

            // Crear la solicitud de canje
            $solicitud = SolicitudesCanje::create([
                'idSolicitudCanje' => $idSolicitudCanje,
                'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
                'idTecnico' => $validatedData['idTecnico'],
                'idEstadoSolicitudCanje' => 1, // Estado por defecto: Pendiente
                'fechaHoraEmision_VentaIntermediada' => $venta->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $diasTranscurridos,
                'puntosComprobante_SolicitudCanje' => $puntosComprobante,
                'puntosCanjeados_SolicitudCanje' => $puntosCanjeados,
                'puntosRestantes_SolicitudCanje' => $puntosRestantes,
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

            Log::info($venta);

            DB::commit();

            return response()->json([
                'message' => 'Solicitud de canje creada exitosamente.',
                'data' => $solicitud,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear la solicitud de canje.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function eliminarSolicitud(Request $request, $idSolicitudCanje)
    {
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

            // Eliminar las recompensas asociadas a la solicitud
            SolicitudCanjeRecompensa::where('idSolicitudCanje', $idSolicitudCanje)->delete();

            // Eliminar la solicitud
            $solicitud->delete();

            return response()->json([
                'message' => 'Solicitud eliminada exitosamente.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Solicitud no encontrada.',
            ], 404);
        } catch (\Exception $e) {
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
                'puntosComprobante_Canje' => $solicitudCanje->puntosComprobante_SolicitudCanje,
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
 
             // Actualizar estado de solicitud canje
             $solicitudCanje->update([
                 'idEstadoSolicitudCanje' => 3, // Rechazado
                 'idUser' => $idUser,
                 'comentario_SolicitudCanje' => $comentarioSolicitudCanje ?? 'Sin comentario',
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
}
