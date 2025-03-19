<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tecnico;
use App\Http\Controllers\TecnicoController;

class TecnicoFactory extends Factory
{

    protected $model = Tecnico::class;

    public function definition(): array
    {   
        $puntos = $this->faker->numberBetween(0, 100000);
        $tecnicoController = new TecnicoController();

        return [
            'idTecnico' => $this->faker->unique()->regexify('[1-9]{8}'),  // Ajustado para generar números de 8 dígitos
            'nombreTecnico' => $this->faker->firstName() . ' ' . $this->faker->lastName(), // Genera solo nombre y apellido
            'celularTecnico' => $this->faker->regexify('9[1-9]{8}'), 
            'fechaNacimiento_Tecnico' => $this->faker->dateTimeBetween('1970-01-01', 'now')->format('Y-m-d'), 
            'totalPuntosActuales_Tecnico' => $this->faker->numberBetween(0, 200), // Asumiendo un rango de puntos posibles
            'historicoPuntos_Tecnico' => $puntos, // Ajustado para generar un número de puntos históricos
            'idRango' => $tecnicoController->getIDRango($puntos), 
        ];
    }
}
