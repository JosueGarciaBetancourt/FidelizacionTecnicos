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
        /*
            $table->string('idCanje', 10); //CANJ-00001 (se genera automáticamente)
            $table->string('idVentaIntermediada', 13);
            $table->dateTime('fechaHora_Canje')->useCurrent();
            $table->dateTime('fechaHoraCargaComprobante_Canje');
            $table->integer('diasTranscurridos_Canje')->unsigned(); 
            $table->integer('puntosComprobante_Canje')->unsigned();
            $table->integer('puntosCanjeados_Canje')->unsigned();
            $table->integer('puntosRestantes_Canje')->unsigned(); 
            $table->string('rutaPDF_Canje')->nullable(); //public/detallesCanje/CANJ-00001.pdf
            $table->unsignedBigInteger('idUser');
        */

        $canjeController = new CanjeController();

        $canjes = [
            [   // Primer canje (completo)
                'idVentaIntermediada' => 'F001-00000072',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-10 10:00:00',
                'fechaHora_Canje' => '2024-10-25 10:00:00',
                'diasTranscurridos_Canje' => 15,
                'puntosComprobante_Canje' => 75,
                'puntosCanjeados_Canje' => 75,
                'puntosRestantes_Canje' => 0,
                'idUser' => 1, // Admin
            ],
            [
                // Primer canje (parcial)
                'idVentaIntermediada' => 'F001-00000077',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 10:00:00',
                'fechaHora_Canje' => '2024-10-25 10:00:00',
                'diasTranscurridos_Canje' => 5,
                'puntosComprobante_Canje' => 450,
                'puntosCanjeados_Canje' => 440,
                'puntosRestantes_Canje' => 10,
                'idUser' => 1, // Admin
            ],
            [
                // Segundo canje (completo)
                'idVentaIntermediada' => 'F001-00000077',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 10:00:00',
                'fechaHora_Canje' => '2024-10-30 10:00:00',
                'diasTranscurridos_Canje' => 10,
                'puntosComprobante_Canje' => 10,
                'puntosCanjeados_Canje' => 10,
                'puntosRestantes_Canje' => 0,
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
