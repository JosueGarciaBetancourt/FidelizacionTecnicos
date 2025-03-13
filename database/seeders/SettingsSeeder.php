<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'emailDomain',
                'value' => 'dimacof.com',
            ],
            [
                'key' => 'adminUsername',
                'value' => 'admin', 
            ],
            [
                'key' => 'maxdaysCanje',
                'value' => 90,
            ],
            [
                'key' => 'puntosMinRangoPlata',
                'value' => 0,
            ],
            [
                'key' => 'puntosMinRangoOro',
                'value' => 20000,
            ],
            [
                'key' => 'puntosMinRangoBlack',
                'value' => 60000,
            ],
            [
                'key' => 'unidadesRestantesRecompensasNotificacion',
                'value' => 15,
            ],
            [
                'key' => 'diasAgotarVentaIntermediadaNotificacion',
                'value' => 7,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        Cache::forget('settings_cache');
    }
}
