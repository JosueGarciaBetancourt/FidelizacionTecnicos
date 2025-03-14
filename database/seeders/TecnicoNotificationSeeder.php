<?php

namespace Database\Seeders;

use App\Models\TecnicoNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TecnicoNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $tecnicosNotifications = [
            // Cambio de rango
            [  
                'idTecnico' => '77043114',
                'idVentaIntermediada' => null,
                'description' => '¡Felicidades! Subiste de rango. Ahora eres rango Oro',
            ],
            // Venta intermediada cerca de agotarse 
            [  
                'idTecnico' => '77043114',
                'idVentaIntermediada' => 'F001-00000076',
                'description' => 'Venta intermediada con 7 días para agotarse', // Los días para agotarse pueden modificarse
            ],
        ];

        foreach ($tecnicosNotifications as $tecNoti) {
            TecnicoNotification::create($tecNoti);
        }
    }
}
