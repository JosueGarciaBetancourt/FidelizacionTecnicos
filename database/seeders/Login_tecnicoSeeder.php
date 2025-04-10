<?php

namespace Database\Seeders;

use App\Models\Login_Tecnico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tecnico;
use Illuminate\Support\Collection;

class Login_tecnicoSeeder extends Seeder
{
    public function run(): void
    {   
        // Crear login inhabilitado para pruebas
        Login_Tecnico::forceCreate([
            'idTecnico' => '11111111',
            'password' => Hash::make('11111111'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        // Obtener solo los IDs de técnicos que aún no tienen login
        $existingLoginIds = DB::table('login_tecnicos')->pluck('idTecnico')->toArray();

        $newIDTecnicos = Tecnico::whereNotIn('idTecnico', $existingLoginIds)->pluck('idTecnico');

        if ($newIDTecnicos->isNotEmpty()) {
            // Preparar los datos para inserción masiva
            $loginData = $newIDTecnicos->map(function ($idTecnico) {
                return [
                    'idTecnico' => $idTecnico,
                    'password' => Hash::make($idTecnico),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Inserción masiva
            DB::table('login_tecnicos')->insert($loginData);
        }
    }
}