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
            'name' => 'Vendedor 1',
            'email' => 'vendedor1@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);

        User::create([
            'name' => 'Vendedor 2',
            'email' => 'vendedor2@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);
        
        User::create([
            'name' => 'Guillermo Peña',
            'email' => 'guillermo@dimacof.com',
            'password' => Hash::make('continental'),
            'profile' => 'Vendedor'
        ]);
    }
}
