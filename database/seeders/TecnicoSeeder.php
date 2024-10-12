<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        Tecnico::create([
            'idTecnico' => '77043114',
            'nombreTecnico' => 'Josué Daniel García Betancourt',
            'celularTecnico' =>'964866527', 
            'oficioTecnico' => 'Enchapador',
            'fechaNacimiento_Tecnico' => '2005-05-16',
            'totalPuntosActuales_Tecnico' => 230,
            'historicoPuntos_Tecnico' => 23000,
            'rangoTecnico' => 'Plata',
        ]);
        
        Tecnico::create([
            'idTecnico' => '77665544',
            'nombreTecnico' => 'Manuel Carrasco',
            'celularTecnico' =>'999888777', 
            'oficioTecnico' => 'Albañil',
            'fechaNacimiento_Tecnico' => '1998-10-13',
            'totalPuntosActuales_Tecnico' => 0,
            'historicoPuntos_Tecnico' => 0,
            'rangoTecnico' => 'Plata',
        ]);

        Tecnico::factory(10)->create();
    }
}
