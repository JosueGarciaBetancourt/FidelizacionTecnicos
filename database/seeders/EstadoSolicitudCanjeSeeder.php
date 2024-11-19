<?php

namespace Database\Seeders;

use App\Models\EstadosSolicitudCanje;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSolicitudCanjeSeeder extends Seeder
{
    public function run(): void
    {
        $estadossolicitudcanjes = [
            [
                'idEstadoSolicitudCanje' => 1,
                'nombre_EstadoSolicitudCanje' => 'Pendiente',
            ],
            [
                'idEstadoSolicitudCanje' => 2,
                'nombre_EstadoSolicitudCanje' => 'Aprobado',
            ],
            [
                'idEstadoSolicitudCanje' => 3,
                'nombre_EstadoSolicitudCanje' => 'Rechazado',
            ],
        ];

        foreach ($estadossolicitudcanjes as $estSolCan) {
            EstadosSolicitudCanje::create($estSolCan);
        }
    }
}
