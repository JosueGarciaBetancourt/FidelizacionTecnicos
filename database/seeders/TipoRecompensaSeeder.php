<?php

namespace Database\Seeders;

use App\Models\TipoRecompensa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoRecompensaSeeder extends Seeder
{
    public function run(): void
    {
        TipoRecompensa::create([
            'idTipoRecompensa' => 1,
            'nombre_TipoRecompensa' => 'Efectivo',
        ]);

        $tiposrecompensas = [
            [   
                'idTipoRecompensa' => 2,
                'nombre_TipoRecompensa' => 'Accesorio',
            ],
            [   
                'idTipoRecompensa' => 3,
                'nombre_TipoRecompensa' => 'EPP',
            ],
            [   
                'idTipoRecompensa' => 4,
                'nombre_TipoRecompensa' => 'Herramienta',
            ],
        ];

        foreach ($tiposrecompensas as $tipoRecompensa) {
            TipoRecompensa::create($tipoRecompensa);
        }
    }
}
