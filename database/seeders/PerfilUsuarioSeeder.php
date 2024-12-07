<?php

namespace Database\Seeders;

use App\Models\PerfilUsuario;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerfilUsuarioSeeder extends Seeder
{
    public function run(): void
    {

        $perfilesUsuarios = [
            [
                'nombre_PerfilUsuario' => 'Administrador',
            ],
            [
                'nombre_PerfilUsuario' => 'Vendedor',
            ],
        ];

        foreach ($perfilesUsuarios as $perfil) {
            PerfilUsuario::create($perfil);
        }
    }
}
