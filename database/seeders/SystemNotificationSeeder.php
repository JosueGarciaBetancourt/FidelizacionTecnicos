<?php

namespace Database\Seeders;

use App\Models\SystemNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $systemNotifications = [
            [  
                'icon' => 'request_page',
                'title' => 'Nueva solicitud de canje',
                'tblToFilter' => 'tblSolicitudesAppCanje',
                'item' => 'SOLICANJ-00003',
                'description' => 'recibida desde app móvil',
                'routeToReview' => 'solicitudescanjes.create',
            ],
            [  
                'icon' => 'timer',
                'tblToFilter' => 'tblVentasIntermediadas',
                'title' => 'Venta cerca de agotarse',
                'item' => 'F001-00000072',
                'description' => 'está a 7 días de agotarse',
                'routeToReview' => 'ventasIntermediadas.create',
            ],
            [  
                'icon' => 'workspace_premium',
                'tblToFilter' => 'tblTecnicos',
                'title' => 'Cambio de rango de técnico',
                'item' => '77043114 | Josué Daniel García Betancourt',
                'description' => 'subió a rango Oro',
                'routeToReview' => 'tecnicos.create',
            ],
        ];

        foreach ($systemNotifications as $sysNoti) {
            SystemNotification::create($sysNoti);
        }
    }
}
