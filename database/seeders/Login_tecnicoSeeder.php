<?php

/*
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Log;

class Login_tecnicoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todos los técnicos existentes
        $tecnicos = Tecnico::all();

        //Log::info('Número de técnicos encontrados: ' . $tecnicos->count());

        foreach ($tecnicos as $tecnico) {
            // Verificar si ya existe un login para este técnico
            $existingLogin = DB::table('login_tecnicos')->where('idTecnico', $tecnico->idTecnico)->first();

            if (!$existingLogin) {
                DB::table('login_tecnicos')->insert([
                    'idTecnico' => $tecnico->idTecnico,
                    'password' => Hash::make($tecnico->idTecnico), // Contraseña de prueba
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                //Log::info('Login creado para técnico: ' . $tecnico->idTecnico);
            } else {
                //Log::info('Login ya existe para técnico: ' . $tecnico->idTecnico);
            }
        }

        $totalLogins = DB::table('login_tecnicos')->count();
        Log::info('Total de logins en la tabla: ' . $totalLogins);
    }
}
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tecnico;
use Illuminate\Support\Collection;

class Login_tecnicoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener solo los IDs de técnicos que aún no tienen login
        $existingLoginIds = DB::table('login_tecnicos')
            ->pluck('idTecnico')
            ->toArray();

        $newTecnicos = Tecnico::whereNotIn('idTecnico', $existingLoginIds)
            ->pluck('idTecnico');

        if ($newTecnicos->isNotEmpty()) {
            // Preparar los datos para inserción masiva
            $loginData = $newTecnicos->map(function ($idTecnico) {
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