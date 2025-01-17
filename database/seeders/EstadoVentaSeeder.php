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
                'nombre_EstadoVenta' => 'En espera', //1 a 90 días transcurridos
            ],
            [
                //'idEstadoVenta' => 2,
                'nombre_EstadoVenta' => 'Redimido (parcial)', // Mínimo un canje asociado a la venta
            ],
            [
                //'idEstadoVenta' => 3,
                'nombre_EstadoVenta' => 'Redimido (completo)', // Canjear todos los puntos dentro de los 90 días
            ],
            [
                //'idEstadoVenta' => 4,
                'nombre_EstadoVenta' => 'Tiempo Agotado', // Tiene que ser una venta En espera ó Redimido (parcial) y supera los 90 días
            ],
            [
                //'idEstadoVenta' => 5,
                'nombre_EstadoVenta' => 'En espera (solicitud desde app)', //1 a 90 días transcurridos y proviene de una solicitud desde la app
            ],
        ];

        foreach ($estadosventas as $estVen) {
            EstadoVenta::create($estVen);
        }
    }
}
