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
                'descripcion_Oficio' => 'Esto es un ejemplo de descripción del oficio Albañil',
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
                'nombre_Oficio' => 'Instalador de ventanas y puertas',
            ],
            [
                'idOficio' => 8,
                'nombre_Oficio' => 'Pintor',
            ],
            [
                'idOficio' => 9,
                'nombre_Oficio' => 'Soldador',
            ],
            [
                'idOficio' => 10,
                'nombre_Oficio' => 'Plomero',
                'descripcion_Oficio' => 'Se especializa en la instalación y reparación de sistemas de agua y drenaje.',
            ],
            [
                'idOficio' => 11,
                'nombre_Oficio' => 'Techador',
                'descripcion_Oficio' => 'Responsable de instalar y reparar techos de diferentes tipos.',
            ],
            [
                'idOficio' => 12,
                'nombre_Oficio' => 'Jardinero',
                'descripcion_Oficio' => 'Encargado del diseño y mantenimiento de jardines y áreas verdes.',
            ],
            [
                'idOficio' => 13,
                'nombre_Oficio' => 'Mecánico',
                'descripcion_Oficio' => 'Especialista en la reparación y mantenimiento de vehículos y maquinaria.',
            ],
            [
                'idOficio' => 14,
                'nombre_Oficio' => 'Técnico en refrigeración',
                'descripcion_Oficio' => 'Encargado de instalar, mantener y reparar sistemas de aire acondicionado y refrigeración.',
            ],
            [
                'idOficio' => 15,
                'nombre_Oficio' => 'Vidriero',
                'descripcion_Oficio' => 'Trabaja con vidrios y cristales en ventanas, puertas y estructuras decorativas.',
            ],
            [
                'idOficio' => 16,
                'nombre_Oficio' => 'Yesero',
                'descripcion_Oficio' => 'Aplica yeso en superficies interiores para acabados decorativos o funcionales.',
            ],
        ];

        foreach ($oficios as $oficio) {
            Oficio::create($oficio);
        }
    }
}
