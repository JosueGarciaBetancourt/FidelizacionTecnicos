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
            'name' => 'vendedor1',
            'email' => 'vendedor1@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);

        User::create([
            'name' => 'vendedor2',
            'email' => 'vendedor2@dimacof.com',
            'password' => Hash::make('12345678'),
            'profile' => 'Vendedor'
        ]);
    }
}
