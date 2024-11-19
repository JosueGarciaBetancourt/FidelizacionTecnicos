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
                'idVentaIntermediada' => 'F001-00000072',
                'idTecnico' => '77043114', // Josué García
                'idEstadoSolicitudCanje' => 1, // Pendiente
                //'fecha_SolicitudCanje' => now(),
                //'comentario_SolicitudCanje' => ,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idVentaIntermediada' => 'F001-00000072',
                'idTecnico' => '77665544', // Manuel Carrasco
                'idEstadoSolicitudCanje' => 2, // Aprobado
                //'fecha_SolicitudCanje' => now(),
                'comentario_SolicitudCanje' => 'Comentario opcional del usuario cuando aprueba o rechaza la solicitud',
            ],
        ];

        foreach ($solicitudescanjes as $solCanj) {
            SolicitudesCanje::create($solCanj);
        }
    }
}
