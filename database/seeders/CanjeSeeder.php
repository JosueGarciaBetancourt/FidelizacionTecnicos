<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Canje;
use App\Http\Controllers\CanjeController;

class CanjeSeeder extends Seeder
{
    public function run(): void
    {
        $canjeController = new CanjeController();

        $canjes = [
            [   
                'idVentaIntermediada' => 'F001-00000072',
                'idRecompensa' => 'RECOM-002',
                'cantidadRecompensa_Canje' => 2,
                'totalPuntosCanjeados_Canje' => 70,
                'diasTranscurridos_Canje' => 30,
                //'rutaPDF_Canje' => "public/PDFCanjes/{$nuevoIdCanje1}-F001-00000072.pdf",
                'fechaHora_Canje' => "2024-05-30 09:25:11",
                'idUser' => 1, // Admin
            ],
            [
                'idVentaIntermediada' => 'F001-00000072',
                'idRecompensa' => 'RECOM-003',
                'cantidadRecompensa_Canje' => 1,
                'totalPuntosCanjeados_Canje' => 5,
                'diasTranscurridos_Canje' => 30,
                //'rutaPDF_Canje' => "public/PDFCanjes/{$nuevoIdCanje2}-F001-00000072.pdf",
                'fechaHora_Canje' => "2024-05-30 09:25:11",
                'idUser' => 1, // Admin
            ],
        ];

        foreach ($canjes as $canje) {
            $canje['idCanje'] = $canjeController->generarIdCanje();
            $canje['rutaPDF_Canje'] = $canjeController->generarRutaPDFCanje();
            Canje::create($canje);
        }
    }
}
