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
            'fechaNacimiento_Tecnico' => '2005-05-16',
            'totalPuntosActuales_Tecnico' => 900, // Suma de puntos de ventas intermediadas En espera y Redimido (parcial)
            'historicoPuntos_Tecnico' => 1025, // Suma de los puntos de todas las ventas intermediadas
            'rangoTecnico' => 'Plata', // Menor a 24000 (más detalle en TecnicoController.php)
        ]);
        
        Tecnico::create([
            'idTecnico' => '77665544',
            'nombreTecnico' => 'Raul Torre Medina',
            'celularTecnico' =>'999888777', 
            'fechaNacimiento_Tecnico' => '1998-10-13',
            'totalPuntosActuales_Tecnico' => 0,
            'historicoPuntos_Tecnico' => 575,
            'rangoTecnico' => 'Plata', // Menor a 24000 (más detalle en TecnicoController.php)
        ]);

        Tecnico::factory(50)->create();
    }
}
