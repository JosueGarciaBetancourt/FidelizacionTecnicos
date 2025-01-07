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
            'idVentaIntermediada' => 'F' . $this->faker->randomElement(['001', '002']) . '-' . str_pad($this->faker->unique()->numberBetween(1, 999999), 8, '0', STR_PAD_LEFT),
            'idTecnico' => '77043114',
            'nombreTecnico' => $this->faker->name,
            'tipoCodigoCliente_VentaIntermediada' => $this->faker->randomElement(['RUC', 'DNI']),
            'codigoCliente_VentaIntermediada' => $this->faker->numerify('###########'),
            'nombreCliente_VentaIntermediada' => $this->faker->name,
            'fechaHoraEmision_VentaIntermediada' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'montoTotal_VentaIntermediada' => $this->faker->randomFloat(2, 50, 1000),
            'puntosGanados_VentaIntermediada' => $this->faker->numberBetween(50, 500),
            'puntosActuales_VentaIntermediada' => $this->faker->numberBetween(0, 500),
            'idEstadoVenta' => $this->faker->randomElement([1, 2, 3]), // 1: Pendiente, 2: En proceso, 3: Redimido
        ];
    }
}
