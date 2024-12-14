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
            'email' => env('ADMIN_EMAIL', 'admin@dimacof.com'),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Vendedor Guillermo Peña',
            'email' => 'guillermo@dimacof.com',
            'password' => Hash::make('continental'),
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Raúl Torre',
            'email' => 'raul@dimacof.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
