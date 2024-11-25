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
use Illuminate\Support\Facades\Log;

class SolicitudCanjeController extends Controller
{
    public function crearSolicitud(Request $request)
    {
        $validatedData = $request->validate([
            'idVentaIntermediada' => 'required|string|exists:VentasIntermediadas,idVentaIntermediada',
            'idTecnico' => 'required|string|exists:Tecnicos,idTecnico',
            'recompensas' => 'required|array',
            'recompensas.*.idRecompensa' => 'required|string|exists:Recompensas,idRecompensa',
            'recompensas.*.cantidad' => 'required|integer|min:1',
            'recompensas.*.costoRecompensa' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generar un ID único para la solicitud
            $idSolicitudCanje = 'SOLICANJ-' . str_pad(SolicitudesCanje::count() + 1, 5, '0', STR_PAD_LEFT);

            // Crear la solicitud de canje
            $solicitud = SolicitudesCanje::create([
                'idSolicitudCanje' => $idSolicitudCanje,
                'idVentaIntermediada' => $validatedData['idVentaIntermediada'],
                'idTecnico' => $validatedData['idTecnico'],
                'idEstadoSolicitudCanje' => 1, // Estado por defecto: Pendiente
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
