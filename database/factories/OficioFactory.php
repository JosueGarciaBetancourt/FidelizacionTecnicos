<?php

namespace Database\Factories;

use App\Models\Oficio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oficio>
 */
class OficioFactory extends Factory
{
    protected $model = Oficio::class;

    public function definition(): array
    {
        return [
            'nombre_Oficio' => $this->faker->word, // Puedes usar un faker realista como 'Albañil', 'Carpintero', etc.
            'descripcion_Oficio' => $this->faker->sentence, // Una breve descripción generada aleatoriamente.
        ];
    }
}
