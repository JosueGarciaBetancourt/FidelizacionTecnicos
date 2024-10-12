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
        
        $recompensas = [
            [   
                'tipoRecompensa' => 'Efectivo',
                'descripcionRecompensa' => 'Sin descripción',
                'costoPuntos_Recompensa' => 1,
            ],
            [
                'tipoRecompensa' => 'EPP',
                'descripcionRecompensa' => 'Par de rodilleras para cerámica',
                'costoPuntos_Recompensa' => 35,
            ],
            [
                'tipoRecompensa' => 'Accesorio',
                'descripcionRecompensa' => 'LLavero DIMACOF',
                'costoPuntos_Recompensa' => 5,
            ],
            [
                'tipoRecompensa' => 'Herramienta',
                'descripcionRecompensa' => 'Juego de destornilladores',
                'costoPuntos_Recompensa' => 40,
            ],
            [
                'tipoRecompensa' => 'EPP',
                'descripcionRecompensa' => 'Casco de seguridad',
                'costoPuntos_Recompensa' => 25,
            ],
            [
                'tipoRecompensa' => 'Herramienta',
                'descripcionRecompensa' => 'Taladro inalámbrico',
                'costoPuntos_Recompensa' => 60,
            ],
            [
                'tipoRecompensa' => 'Accesorio',
                'descripcionRecompensa' => 'Caja de herramientas vacía',
                'costoPuntos_Recompensa' => 50,
            ],
            [
                'tipoRecompensa' => 'Herramienta',
                'descripcionRecompensa' => 'Palustre de 9225mmREF.: BK1040 Marca: BRICKELL',
                'costoPuntos_Recompensa' => 100,
            ],
        ];

        foreach ($recompensas as $recompensa) {
            $recompensa['idRecompensa'] = $recompensaController->generarIdRecompensa();
            Recompensa::create($recompensa);
        }
    }
}
