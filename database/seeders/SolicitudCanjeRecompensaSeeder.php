<?php

namespace Database\Seeders;

use App\Models\SolicitudCanjeRecompensa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitudCanjeRecompensaSeeder extends Seeder
{
    public function run(): void
    {
        $solicitudesCanjeRecompensas = [
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idRecompensa' => 'RECOM-001', // Pulsera de silicona
                'cantidad' => 5,
                'costoRecompensa' => 1,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idRecompensa' => 'RECOM-007', // Caja de herramientas vacía
                'cantidad' => 8,
                'costoRecompensa' => 50,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idRecompensa' => 'RECOM-005', // Casco de seguridad
                'cantidad' => 1,
                'costoRecompensa' => 25,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00003',
                'idRecompensa' => 'RECOM-001', // Pulsera de silicona
                'cantidad' => 5,
                'costoRecompensa' => 1,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00004',
                'idRecompensa' => 'RECOM-007', // Caja de herramientas vacía
                'cantidad' => 2,
                'costoRecompensa' => 50,
            ],
            
        ];

        foreach ($solicitudesCanjeRecompensas as $solcanjrecom) {
            SolicitudCanjeRecompensa::create($solcanjrecom);
        }
    }
}
