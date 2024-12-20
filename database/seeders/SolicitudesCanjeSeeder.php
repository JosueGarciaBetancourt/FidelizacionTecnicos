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
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => '2024-11-10 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 13, 
                'puntosComprobante_SolicitudCanje' => 500,  
                'puntosCanjeados_SolicitudCanje' => 400,
                'puntosRestantes_SolicitudCanje' => 100, 
                'fechaHora_SolicitudCanje' => '2024-11-23 10:00:00',
                //'comentario_SolicitudCanje' => ,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idVentaIntermediada' => 'F001-00000555', //En espera
                'idTecnico' => '77665544', // Manuel Carrasco
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'idUser' => 2, // Administrador
                'fechaHoraEmision_VentaIntermediada' => '2024-11-10 10:00:00',
                'diasTranscurridos_SolicitudCanje' => 13, 
                'puntosComprobante_SolicitudCanje' => 5,  
                'puntosCanjeados_SolicitudCanje' => 5,
                'puntosRestantes_SolicitudCanje' => 0, 
                'fechaHora_SolicitudCanje' => '2024-11-23 10:00:00',
                //'comentario_SolicitudCanje' => ,
            ],
        ];

        foreach ($solicitudescanjes as $solCanj) {
            SolicitudesCanje::create($solCanj);
        }
    }
}
