<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Oficio;

class OficioSeeder extends Seeder
{
    public function run(): void
    {
        $oficios = [
            [
                'idOficio' => 1,
                'nombre_Oficio' => 'Albañil',
            ],
            [
                'idOficio' => 2,
                'nombre_Oficio' => 'Carpintero',
            ],
            [
                'idOficio' => 3,
                'nombre_Oficio' => 'Cerrajero',
            ],
            [
                'idOficio' => 4,
                'nombre_Oficio' => 'Electricista',
            ],
            [
                'idOficio' => 5,
                'nombre_Oficio' => 'Enchapador',
            ],
            [
                'idOficio' => 6,
                'nombre_Oficio' => 'Gasfitero',
            ],
            [
                'idOficio' => 7,
                'nombre_Oficio' => 'Instalador de pisos',
            ],
            [
                'idOficio' => 8,
                'nombre_Oficio' => 'Instalador de ventanas y puertas',
            ],
            [
                'idOficio' => 9,
                'nombre_Oficio' => 'Pintor',
            ],
            [
                'idOficio' => 10,
                'nombre_Oficio' => 'Soldador',
            ],
            [
                'idOficio' => 11,
                'nombre_Oficio' => 'Techador',
            ],
            [
                'idOficio' => 12,
                'nombre_Oficio' => 'Yesero',
            ],
        ];

        foreach ($oficios as $oficio) {
            Oficio::create($oficio);
        }
    }
}
