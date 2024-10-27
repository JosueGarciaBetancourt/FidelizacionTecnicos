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
            'name' => 'Administrador',
            'email' => 'admin@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Administrador'
        ]);

        User::create([
            'name' => 'Vendedor Raul',
            'email' => 'vendedorRaul@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);

        User::create([
            'name' => 'Vendedor Jeison',
            'email' => 'vendedorJeison@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);
    }
}
