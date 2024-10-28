<?php

namespace Database\Factories;

use App\Models\Tecnico;
use App\Http\Controllers\TecnicoController;
use Illuminate\Database\Eloquent\Factories\Factory;

class TecnicoFactory extends Factory
{
protected $model = Tecnico::class;

    public function definition(): array
    {   
        $puntos = $this->faker->numberBetween(0, 90000);
        $tecnicoController = new TecnicoController();

        return [
            'idTecnico' => $this->faker->unique()->regexify('[1-9]{8}'),  // Ajustado para generar números de 8 dígitos
            'nombreTecnico' => $this->faker->name(),
            'celularTecnico' => $this->faker->regexify('[1-9]{8}'), 
            'oficioTecnico' => $this->faker->randomElement(['Enchapador','Albañil', 'Gasfitero',
                                                            'Enchapador/Albañil', 'Enchapador/Gasfitero'],
                                                            'Albañil/Gasfitero', 'Enchapador/Albañil/Gasfitero'),
            'fechaNacimiento_Tecnico' => $this->faker->dateTimeBetween('1970-01-01', 'now')->format('Y-m-d'), 
            'totalPuntosActuales_Tecnico' => $this->faker->numberBetween(0, 200), // Asumiendo un rango de puntos posibles
            'historicoPuntos_Tecnico' => $puntos, // Ajustado para generar un número de puntos históricos
            'rangoTecnico' => $tecnicoController->getRango($puntos), 
        ];
    }
}
