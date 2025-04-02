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
            'colorTexto_TipoRecompensa' => '#139161',
            'colorFondo_TipoRecompensa' => '#13916126',
        ]);

        $tiposrecompensas = [
            [   
                'idTipoRecompensa' => 2,
                'nombre_TipoRecompensa' => 'Accesorio',
                'nombre_TipoRecompensa' => 'Accesorio',
                'colorTexto_TipoRecompensa' => '#9C27B0',
                'colorFondo_TipoRecompensa' => '#9C27B026',
            ],
            [   
                'idTipoRecompensa' => 3,
                'nombre_TipoRecompensa' => 'EPP',
                'colorTexto_TipoRecompensa' => '#2196F3',
                'colorFondo_TipoRecompensa' => '#2196F326',
            ],
            [   
                'idTipoRecompensa' => 4,
                'nombre_TipoRecompensa' => 'Herramienta',
                'colorTexto_TipoRecompensa' => '#FF9800',
                'colorFondo_TipoRecompensa' => '#FF980026',
            ],
            [   
                'idTipoRecompensa' => 5,
                'nombre_TipoRecompensa' => 'Probar Eliminar Tipo Recompensa',
            ],
        ];

        foreach ($tiposrecompensas as $tipoRecompensa) {
            TipoRecompensa::create($tipoRecompensa);
        }
    }
}
