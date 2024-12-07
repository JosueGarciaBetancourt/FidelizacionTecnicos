<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'idPerfilUsuario' => 1,
            'name' => 'Admin',
            'email' => 'admin@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Administrador'
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Raúl Torre',
            'email' => 'raul@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);
        
        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Vendedor Guillermo Peña',
            'email' => 'guillermo@dimacof.com',
            'password' => Hash::make('continental'),
            'profile' => 'Vendedor'
        ]);
    }
}
