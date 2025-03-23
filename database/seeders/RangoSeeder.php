<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Seeder;
use App\Models\Rango;

class RangoSeeder extends Seeder
{
    public function run(): void
    {
        $rangos = [
            [
                'idRango' => 1,
                'nombre_Rango' => 'Sin rango',
                'descripcion_Rango' => 'Es el rango que se le asignan a los técnicos que no cumplen con los puntos mínimos',
                'puntosMinimos_Rango' => 0,
            ],
            [
                'idRango' => 2,
                'nombre_Rango' => 'Plata',
                'puntosMinimos_Rango' => 0,
            ],
            [
                'idRango' => 3,
                'nombre_Rango' => 'Oro',
                'puntosMinimos_Rango' => 24000,
            ],
            [
                'idRango' => 4,
                'nombre_Rango' => 'Black',
                'puntosMinimos_Rango' => 60000,
            ],
        ];

        foreach ($rangos as $rango) {
            Rango::create($rango);
        }

        Cache::forget('settings_cache');
    }
}
