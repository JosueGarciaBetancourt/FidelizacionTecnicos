<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recompensa;
use App\Http\Controllers\RecompensaController;

class RecompensaSeeder extends Seeder
{
    public function run(): void
    {
        $recompensaController = new RecompensaController();
        
        Recompensa::create([
            'idRecompensa' => 'RECOM-000', // sin espacio extra
            'idTipoRecompensa' => 1,
            'descripcionRecompensa' => 'Dinero en efectivo',
            'costoPuntos_Recompensa' => 1,
            'stock_Recompensa' => null,
            'deleted_at' => now(), // Marca la recompensa como eliminada lógicamente
        ]);

        $recompensas = [
            [   
                'idTipoRecompensa' => 2,
                'descripcionRecompensa' => 'Pulsera de silicona',
                'costoPuntos_Recompensa' => 1,
                'stock_Recompensa' => 50,
            ],
            [
                'idTipoRecompensa' => 3,
                'descripcionRecompensa' => 'Par de rodilleras para cerámica',
                'costoPuntos_Recompensa' => 35,
                'stock_Recompensa' => 25,
            ],
            [
                'idTipoRecompensa' => 2,
                'descripcionRecompensa' => 'LLavero DIMACOF',
                'costoPuntos_Recompensa' => 5,
                'stock_Recompensa' => 85,
            ],
            [
                'idTipoRecompensa' => 4,
                'descripcionRecompensa' => 'Juego de destornilladores',
                'costoPuntos_Recompensa' => 40,
                'stock_Recompensa' => 100,
            ],
            [
                'idTipoRecompensa' => 3,
                'descripcionRecompensa' => 'Casco de seguridad',
                'costoPuntos_Recompensa' => 25,
                'stock_Recompensa' => 90,
            ],
            [
                'idTipoRecompensa' => 4,
                'descripcionRecompensa' => 'Taladro inalámbrico',
                'costoPuntos_Recompensa' => 60,
                'stock_Recompensa' => 30,
            ],
            [
                'idTipoRecompensa' => 2,
                'descripcionRecompensa' => 'Caja de herramientas vacía',
                'costoPuntos_Recompensa' => 50,
                'stock_Recompensa' => 40,
            ],
            [
                'idTipoRecompensa' => 4,
                'descripcionRecompensa' => 'Palustre de 9225mm REF.: BK1040 Marca: BRICKELL',
                'costoPuntos_Recompensa' => 50,
                'stock_Recompensa' => 15,
            ],
        ];

        foreach ($recompensas as $recompensa) {
            $recompensa['idRecompensa'] = $recompensaController->generarIdRecompensa();
            Recompensa::create($recompensa);
        }
    }
}
