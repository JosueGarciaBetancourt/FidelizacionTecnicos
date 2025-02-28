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
            'DNI' => '12345678',
            'personalName' => 'Josué Daniel',
            'surname' => 'García Betancourt',
            'fechaNacimiento' => '2002-11-12',
            'correoPersonal' => 'garciabetancourtjosue@gmail.com',
            'celularPersonal' => '999555333',
            'celularCorporativo' => '999888111',
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Vendedor Guillermo Peña',
            'email' => 'guillermo@dimacof.com',
            'password' => Hash::make('continental'),
            'DNI' => '44221100',
            'personalName' => 'Guillermo Eduardo',
            'surname' => 'Peña Santiago',
            'fechaNacimiento' => '1995-11-12',
            'correoPersonal' => 'guille_pe_san@gmail.com',
            'celularPersonal' => '996485224',
            'celularCorporativo' => '999888111',
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Raúl Torre',
            'email' => 'raul@dimacof.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'idPerfilUsuario' => 2,
            'name' => 'Test',
            'email' => 'test@dimacof.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
