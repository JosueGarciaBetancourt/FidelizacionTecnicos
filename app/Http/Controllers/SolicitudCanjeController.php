<?php

namespace App\Http\Controllers;

use App\Models\SolicitudesCanje;
use App\Models\SolicitudCanjeRecompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function aprobarSolicitudCanje($idSolicitudCanje) {
        try {
            $solicitudCanje = SolicitudesCanje::findOrFail($idSolicitudCanje);

            $message = "Aprobando solicitud de canje: " . $solicitudCanje->idSolicitudCanje;
            return response()->json($message);
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al aprobar la la solicitud canje ' . $idSolicitudCanje, 'details' => $e->getMessage()], 500);
        }
    }

    public function rechazarSolicitudCanje($idSolicitudCanje) {
        try {
            $message = "Rechazando solicitud de canje";
            return response()->json($message);
        } catch (\Exception $e) {
            // Manejo de errores en caso de fallo de consulta
            return response()->json(['error' => 'Error al rechazar la la solicitud canje ' . $idSolicitudCanje, 'details' => $e->getMessage()], 500);
        }
    }
}
