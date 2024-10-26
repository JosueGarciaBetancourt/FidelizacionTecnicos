<?php

namespace Database\Seeders;

use App\Models\EstadoVenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoVentaSeeder extends Seeder
{
    public function run(): void
    {
        $estadosventas = [
            [   
                //'idEstadoVenta' => 1,
                'nombre_EstadoVenta' => 'En espera',
            ],
            [
                //'idEstadoVenta' => 2,
                'nombre_EstadoVenta' => 'Redimido (parcial)',
            ],
            [
                //'idEstadoVenta' => 3,
                'nombre_EstadoVenta' => 'Redimido (completo)',
            ],
            [
                 //'idEstadoVenta' => 4,
                 'nombre_EstadoVenta' => 'Tiempo Agotado',
            ],
        ];

        foreach ($estadosventas as $estVen) {
            EstadoVenta::create($estVen);
        }
    }
}
