<?php

namespace Database\Seeders;

use App\Models\Tecnico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TecnicoController;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'idRango' => 2, // Menor a 24000 (más detalle en SettingsServiceProvider.php)
        ]);
        
        Tecnico::create([
            'idTecnico' => '77665544',
            'nombreTecnico' => 'Manuel Carrasco',
            'celularTecnico' =>'999888777', 
            'fechaNacimiento_Tecnico' => '1998-10-13',
            'totalPuntosActuales_Tecnico' => 0,
            'historicoPuntos_Tecnico' => 1775,
            'idRango' => 2, // Menor a 24000 (más detalle en SettingsServiceProvider.php)
        ]);

        Tecnico::create([
            'idTecnico' => '43111949',
            'nombreTecnico' => 'Técnico Guillermo Peña',
            'celularTecnico' =>'964733868',
            'fechaNacimiento_Tecnico' => '1985-04-03',
            'totalPuntosActuales_Tecnico' => 1000, // Suma de puntos de ventas intermediadas En espera y Redimido (parcial)
            'historicoPuntos_Tecnico' => 25000, // Suma de los puntos de todas las ventas intermediadas
            'idRango' => 3, // Menor a 60000 (más detalle en SettingsServiceProvider.php)
        ]);

        Tecnico::forceCreate([
            'idTecnico' => '11111111',
            'nombreTecnico' => 'Inhabilitado',
            'celularTecnico' =>'123456789',
            'fechaNacimiento_Tecnico' => '2000-12-12',
            'totalPuntosActuales_Tecnico' => 0, 
            'historicoPuntos_Tecnico' => 0,
            'idRango' => 2, // Menor a 24000 (más detalle en SettingsServiceProvider.php)
            'deleted_at' => now(),
        ]);

        //Tecnico::factory(97)->create();
    }
}
