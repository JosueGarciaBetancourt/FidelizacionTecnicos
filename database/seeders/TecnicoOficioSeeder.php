<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TecnicoOficio;

class TecnicoOficioSeeder extends Seeder
{
    public function run(): void
    {
        $tecnicosoficios = [
            // 77043114 → Albañil/Enchapador/Gasfitero
            [
                'idTecnico' => '77043114',
                'idOficio' => 1, 
            ],
            [
                'idTecnico' => '77043114',
                'idOficio' => 5, 
            ],
            [
                'idTecnico' => '77043114',
                'idOficio' => 6, 
            ],

            // 77665544 → Electricista/Enchapador/Pintor
            [
                'idTecnico' => '77665544',
                'idOficio' => 4, 
            ],
            [
                'idTecnico' => '77665544',
                'idOficio' => 5, 
            ],
            [
                'idTecnico' => '77665544',
                'idOficio' => 9, 
            ],
        ];

        foreach ($tecnicosoficios as $tec_ofi) {
            TecnicoOficio::create($tec_ofi);
        }

        TecnicoOficio::factory(48)->create();
    }
}
