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
            // Canje completo
            [   // CANJ-00001
                'idVentaIntermediada' => 'F001-00000072',
                'fechaHoraEmision_VentaIntermediada' => '2024-11-08 10:00:00',
                'fechaHora_Canje' => '2024-11-9 10:00:00',
                'diasTranscurridos_Canje' => 1,
                'puntosComprobante_Canje' => 75,
                'puntosCanjeados_Canje' => 75,
                'puntosRestantes_Canje' => 0,
                'comentario_Canje' => 'Ejemplo de comentario de canje',
                'idUser' => 2,
            ],
            // Canje parcial
            [
                // CANJ-00002 
                'idVentaIntermediada' => 'F001-00000076',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 08:25:11',
                'fechaHora_Canje' => '2024-10-25 10:00:00',
                'diasTranscurridos_Canje' => 5,
                'puntosComprobante_Canje' => 450,
                'puntosCanjeados_Canje' => 50,
                'puntosRestantes_Canje' => 400,
                'idUser' => 1,
            ],
            // Canje completo en dos partes
            [
                // CANJ-00003 
                'idVentaIntermediada' => 'F001-00000077',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 10:00:00',
                'fechaHora_Canje' => '2024-10-25 10:00:00',
                'diasTranscurridos_Canje' => 5,
                'puntosComprobante_Canje' => 450,
                'puntosCanjeados_Canje' => 440,
                'puntosRestantes_Canje' => 10,
                'idUser' => 2,
            ],
            [
                // CANJ-00004
                'idVentaIntermediada' => 'F001-00000077',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 10:00:00',
                'fechaHora_Canje' => '2024-10-30 10:00:00',
                'diasTranscurridos_Canje' => 10,
                'puntosComprobante_Canje' => 10,
                'puntosCanjeados_Canje' => 10,
                'puntosRestantes_Canje' => 0,
                'comentario_Canje' => 'El vendedor 1 no se encontraba en caja, vendedor 2 realizó el canje.',
                'idUser' => 3,
            ],
        ];

        foreach ($canjes as $canje) {
            $canje['idCanje'] = $canjeController->generarIdCanje();
            Canje::create($canje);
        }
    }
}
