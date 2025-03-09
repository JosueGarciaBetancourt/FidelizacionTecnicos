<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUsername = Setting::where('key', 'adminUsername')->value('value') ?? 'admin';
        $emailDomain = Setting::where('key', 'emailDomain')->value('value') ?? 'dimacof.com';
        $adminEmail = $adminUsername . '@' . $emailDomain;

        //dd($adminEmail);

        User::create([
            'idPerfilUsuario' => 1,
            'name' => $adminUsername,
            'email' => $adminEmail, 
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
            'email' => 'guillermo' . '@' . $emailDomain,
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
            'name' => 'Raúl',
            'email' => 'vendedor1' . '@' . $emailDomain,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'idPerfilUsuario' => 3,
            'name' => 'Pablito',
            'email' => 'asistente' . '@' . $emailDomain,
            'password' => Hash::make('12345678'),
        ]);
    }
}
