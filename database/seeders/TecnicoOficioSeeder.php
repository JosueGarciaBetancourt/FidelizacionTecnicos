<?php

namespace Database\Seeders;

use App\Models\Oficio;
use App\Models\Tecnico;
use App\Models\TecnicoOficio;
use Illuminate\Database\Seeder;

class TecnicoOficioSeeder extends Seeder
{
    public function run(): void
    {
        // Definimos el número máximo de oficios que podemos asignar a un técnico
        $maxOficiosPorTecnico = 3;

        // Obtenemos todos los técnicos y oficios disponibles
        $tecnicos = Tecnico::withTrashed()->get();
        $oficios = Oficio::all();

        foreach ($tecnicos as $tecnico) {
            // Seleccionamos aleatoriamente entre 1 y el máximo de oficios permitidos
            $cantidadOficios = rand(1, $maxOficiosPorTecnico);
            
            // Seleccionamos oficios aleatorios asegurando que no se repitan
            $oficiosSeleccionados = $oficios->random($cantidadOficios)->pluck('idOficio')->toArray();

            foreach ($oficiosSeleccionados as $idOficio) {
                TecnicoOficio::create([
                    'idTecnico' => $tecnico->idTecnico,
                    'idOficio' => $idOficio,
                ]);
            }
        }
    }
}
