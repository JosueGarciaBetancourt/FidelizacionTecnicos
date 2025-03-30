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
                'descripcion_Rango' => 'Es el rango que se le asignan a los técnicos que no cumplen con los puntos mínimos de los otros rangos',
                'puntosMinimos_Rango' => 0,
                'colorTexto_Rango' => '#FFFFFF',
                'colorFondo_Rango' => '#6A4D57',
            ],
            [
                'idRango' => 2,
                'nombre_Rango' => 'Plata',
                'puntosMinimos_Rango' => 0,
                'colorTexto_Rango' => '#38495a',
                'colorFondo_Rango' => '#C0C1C3',
            ],
            [
                'idRango' => 3,
                'nombre_Rango' => 'Oro',
                'puntosMinimos_Rango' => 24000,
                'colorTexto_Rango' => '#694A01',
                'colorFondo_Rango' => '#FFD700',
            ],
            [
                'idRango' => 4,
                'nombre_Rango' => 'Black',
                'puntosMinimos_Rango' => 60000,
                'colorTexto_Rango' => '#FFFFFF',
                'colorFondo_Rango' => '#333333',
            ],
        ];

        foreach ($rangos as $rango) {
            Rango::create($rango);
        }

        Cache::forget('settings_cache');
    }
}
