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
                'idRecompensa' => 'RECOM-007', // Caja de herramientas
                'cantidad' => 8,
                'costoRecompensa' => 50,
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idRecompensa' => 'RECOM-001', // Pulsera de silicona
                'cantidad' => 5,
                'costoRecompensa' => 1,
            ],
        ];

        foreach ($solicitudesCanjeRecompensas as $solcanjrecom) {
            SolicitudCanjeRecompensa::create($solcanjrecom);
        }
    }
}
