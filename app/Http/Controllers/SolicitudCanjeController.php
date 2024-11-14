<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudCanje;
use App\Models\SolicitudCanjeRecompensas;
use Illuminate\Support\Facades\DB;

class SolicitudCanjeController extends Controller
{
    // Crear solicitud de canje
    public function crearSolicitud(Request $request)
    {
        $request->validate([
            'idTecnico' => 'required|string|exists:Tecnicos,idTecnico',
            'idVentaIntermediada' => 'required|string|exists:VentasIntermediadas,idVentaIntermediada',
            'recompensas' => 'required|array',
            'recompensas.*.idRecompensa' => 'required|string|exists:Recompensas,idRecompensa',
            'recompensas.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Crear la solicitud de canje
            $solicitud = SolicitudCanje::create([
                'idTecnico' => $request->idTecnico,
                'idVentaIntermediada' => $request->idVentaIntermediada,
                'estado' => 'pendiente',
            ]);

            // Guardar cada recompensa en la tabla SolicitudCanjeRecompensas
            foreach ($request->recompensas as $recompensa) {
                SolicitudCanjeRecompensas::create([
                    'idCanje' => $solicitud->idSolicitudCanje,
                    'idRecompensa' => $recompensa['idRecompensa'],
                    'cantidad' => $recompensa['cantidad'],
                    'costoRecompensa' => $this->calcularCosto($recompensa['idRecompensa'], $recompensa['cantidad']),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Solicitud de canje creada con éxito', 'solicitud' => $solicitud], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la solicitud de canje', 'error' => $e->getMessage()], 500);
        }
    }


    // Obtener solicitudes de canje para un técnico específico
    public function obtenerSolicitudes($idTecnico)
    {
        $solicitudes = SolicitudCanje::with('recompensas')
            ->where('idTecnico', $idTecnico)
            ->get();

        return response()->json($solicitudes);
    }

    // Actualizar estado de la solicitud de canje
    public function actualizarEstado(Request $request, $idSolicitud)
    {
        $request->validate(['estado' => 'required|string|in:pendiente,aprobado,rechazado']);

        $solicitud = SolicitudCanje::find($idSolicitud);
        if (!$solicitud) {
            return response()->json(['message' => 'Solicitud no encontrada'], 404);
        }

        $solicitud->estado = $request->estado;
        $solicitud->save();

        return response()->json(['message' => 'Estado de la solicitud actualizado', 'solicitud' => $solicitud]);
    }

    // Calcular el costo total de una recompensa (ejemplo simplificado)
    private function calcularCosto($idRecompensa, $cantidad)
    {
        // Suponiendo que cada recompensa tiene un costo fijo en una tabla
        $recompensa = DB::table('Recompensas')->where('idRecompensa', $idRecompensa)->first();
        return $recompensa ? $recompensa->costo * $cantidad : 0;
    }
}
