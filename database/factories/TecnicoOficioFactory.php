<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TecnicoOficio;
use App\Models\Tecnico;
use App\Models\Oficio;

class TecnicoOficioFactory extends Factory
{
    protected $model = TecnicoOficio::class;

    public function definition(): array
    {   
        return [
            'idTecnico' => Tecnico::inRandomOrder()->value('idTecnico'),  // Obtener un idTecnico aleatorio
            'idOficio' => Oficio::inRandomOrder()->value('idOficio'),     // Obtener un idOficio aleatorio
        ];
    }
}
