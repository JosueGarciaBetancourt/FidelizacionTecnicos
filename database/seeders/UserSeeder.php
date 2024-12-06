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
            'name' => 'Admin',
            'email' => 'admin@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Administrador'
        ]);

        User::create([
            'name' => 'Raúl Torre',
            'email' => 'raul@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);
        
        User::create([
            'name' => 'Vendedor Guillermo Peña',
            'email' => 'guillermo@dimacof.com',
            'password' => Hash::make('continental'),
            'profile' => 'Vendedor'
        ]);
    }
}
