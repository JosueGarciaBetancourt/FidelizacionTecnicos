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
                'description' => 'SOLICANJ-00003 recibida desde app móvil',
                'routeToReview' => 'solicitudescanjes.create',
            ],
            [  
                'icon' => 'timer',
                'title' => 'Venta cerca de agotarse',
                'description' => 'F001-00000072 está a 7 días de agotarse',
                'routeToReview' => 'ventasIntermediadas.create',
            ],
            [  
                'icon' => 'workspace_premium',
                'title' => 'Cambio de rango de técnico',
                'description' => '77043114|Josué Daniel García Betancourt subió a rango Oro',
                'routeToReview' => 'tecnicos.create',
            ],
        ];

        foreach ($systemNotifications as $sysNoti) {
            SystemNotification::create($sysNoti);
        }
    }
}
