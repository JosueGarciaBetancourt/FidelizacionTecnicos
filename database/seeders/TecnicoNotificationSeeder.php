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
                'description' => 'La venta intermediada F001-00000076 se agotará en 7 días', // Los días para agotarse pueden modificarse
            ],
            // Solicitud de canje aprobada
            [  
                'idTecnico' => '77043114',
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'description' => 'Tu solicitud de canje SOLICANJ-00001 fue Aprobada',
            ],
            // Solicitud de canje rechazada
            [  
                'idTecnico' => '77043114',
                'idSolicitudCanje' => 'SOLICANJ-00005',
                'description' => 'Tu solicitud de canje con código SOLICANJ-00005 fue Rechazada',
            ],
        ];

        foreach ($tecnicosNotifications as $tecNoti) {
            TecnicoNotification::create($tecNoti);
        }
    }
}
