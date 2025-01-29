<?php

namespace Database\Seeders;

use App\Models\CanjeRecompensa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CanjeRecompensaSeeder extends Seeder
{
    public function run(): void
    {   
        /*
            idCanje
            idRecompensa
            cantidad
            costoRecompensa
            comentario
            idUser
        */

        $canjesrecompensas = [
            // Canje completo → puntosCanjeados_Canje: 75, puntosRestantes_Canje: 0
            [   
                'idCanje' => 'CANJ-00001',
                'idRecompensa' => 'RECOM-002',
                'cantidad' => 1,
                'costoRecompensa' => 35,
            ],
            [   
                'idCanje' => 'CANJ-00001',
                'idRecompensa' => 'RECOM-004',
                'cantidad' => 1,
                'costoRecompensa' => 40,
            ],
            // Canje parcial → puntosCanjeados_Canje: 50, puntosRestantes_Canje: 400
            [  
                'idCanje' => 'CANJ-00002',
                'idRecompensa' => 'RECOM-007',
                'cantidad' => 1,
                'costoRecompensa' => 50,
            ],
            // CANJE COMPLETO EN DOS PARTES 
            // CANJ-00003 → puntosCanjeados_Canje: 440, puntosRestantes_Canje: 10
            [
                'idCanje' => 'CANJ-00003',
                'idRecompensa' => 'RECOM-008',
                'cantidad' => 8,
                'costoRecompensa' => 50,
            ],
            [
                'idCanje' => 'CANJ-00003',
                'idRecompensa' => 'RECOM-004',
                'cantidad' => 1,
                'costoRecompensa' => 40,
            ],
            // CANJ-00004 → puntosCanjeados_Canje: 10, puntosRestantes_Canje: 0
            [
                'idCanje' => 'CANJ-00004',
                'idRecompensa' => 'RECOM-003',
                'cantidad' => 1,
                'costoRecompensa' => 5,
            ],
            // CANJ-00005, fue aprobado desde una solicitud y le restan algunos puntos a su venta asociada
            [
                'idCanje' => 'CANJ-00005',
                'idRecompensa' => 'RECOM-001',
                'cantidad' => 5,
                'costoRecompensa' => 1,
            ],
            [
                'idCanje' => 'CANJ-00005',
                'idRecompensa' => 'RECOM-007',
                'cantidad' => 8,
                'costoRecompensa' => 40,
            ],
        ];

        foreach ($canjesrecompensas as $canjerecom) {
            CanjeRecompensa::create($canjerecom);
        }
    }
}
