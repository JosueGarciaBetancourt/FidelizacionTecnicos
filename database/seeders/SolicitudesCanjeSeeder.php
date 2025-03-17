<?php

namespace Database\Seeders;

use App\Models\SolicitudesCanje;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesCanjeSeeder extends Seeder
{
    public function run(): void
    {
        $solicitudescanjes = [
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idVentaIntermediada' => 'F001-00000444', //En espera
                'idTecnico' => '77043114', // Josué García
                'idEstadoSolicitudCanje' => 2, // Aprobado
                'fechaHoraEmision_VentaIntermediada' => '2025-01-21 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 1, 
                'puntosComprobante_SolicitudCanje' => 500,  
                'puntosActuales_SolicitudCanje' => 500,
                'puntosCanjeados_SolicitudCanje' => 405,
                'puntosRestantes_SolicitudCanje' => 95, 
                'fechaHora_SolicitudCanje' => '2025-01-22 11:00:00',
                'idUser' => 1,
                'comentario_SolicitudCanje' => 'Ok',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idVentaIntermediada' => 'F001-00000444', //Redimido (parcial)
                'idTecnico' => '77043114', // Josué García
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => '2025-01-21 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 2, 
                'puntosComprobante_SolicitudCanje' => 500,
                'puntosActuales_SolicitudCanje' => 95,
                'puntosCanjeados_SolicitudCanje' => 25,
                'puntosRestantes_SolicitudCanje' => 70, 
                'fechaHora_SolicitudCanje' => '2025-01-23 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00003',
                'idVentaIntermediada' => 'F001-00000555', //En espera
                'idTecnico' => '77665544', // Manuel Carrasco
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => '2025-01-21 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 1, 
                'puntosComprobante_SolicitudCanje' => 1000,  
                'puntosActuales_SolicitudCanje' => 1000,
                'puntosCanjeados_SolicitudCanje' => 5,
                'puntosRestantes_SolicitudCanje' => 995, 
                'fechaHora_SolicitudCanje' => '2025-01-22 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00004',
                'idVentaIntermediada' => 'F001-00000666', //En espera
                'idTecnico' => '77665544', // Manuel Carrasco
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => '2025-01-21 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 1, 
                'puntosComprobante_SolicitudCanje' => 100,  
                'puntosActuales_SolicitudCanje' => 100,
                'puntosCanjeados_SolicitudCanje' => 100,
                'puntosRestantes_SolicitudCanje' => 0, 
                'fechaHora_SolicitudCanje' => '2025-01-22 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
        ];

        foreach ($solicitudescanjes as $solCanj) {
            SolicitudesCanje::create($solCanj);
        }
    }
}
