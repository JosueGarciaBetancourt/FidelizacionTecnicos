<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VentaIntermediadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idVentaIntermediada' => $this->faker->unique()->regexify('F(001|002)-[0-9]{8}'),
            'idTecnico' => '77043114',
            'nombreTecnico' => 'Josué Daniel García Betancourt',
            'tipoCodigoCliente_VentaIntermediada' =>'DNI',
            'codigoCliente_VentaIntermediada' => $this->faker->unique()->numerify('########'),
            'nombreCliente_VentaIntermediada' => $this->faker->name,
            'fechaHoraEmision_VentaIntermediada' => '2025-01-21 10:00:00',
            'montoTotal_VentaIntermediada' => 1000,
            'puntosGanados_VentaIntermediada' => 1000,
            'puntosActuales_VentaIntermediada' => 1000,
            'idEstadoVenta' => 1,
        ];
    }
}
