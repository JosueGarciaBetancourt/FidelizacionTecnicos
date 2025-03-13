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
                'title' => 'Nueva solicitud de canje (seeder)',
                'tblToFilter' => 'tblSolicitudesAppCanje',
                'item' => 'SOLICANJ-00003',
                'description' => 'recibida desde app móvil',
                'routeToReview' => 'solicitudescanjes.create',
            ],
            [  
                'icon' => 'timer',
                'tblToFilter' => 'tblRecompensas',
                'title' => 'Recompensa a punto de agotar stock (seeder)',
                'item' => 'RECOM-001 | Pulsera de silicona',
                'description' => 'tiene 15 o menos unidades restantes',
                'routeToReview' => 'recompensas.create',
            ],
            [  
                'icon' => 'workspace_premium',
                'tblToFilter' => 'tblTecnicos',
                'title' => 'Cambio de rango de técnico (seeder)',
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
