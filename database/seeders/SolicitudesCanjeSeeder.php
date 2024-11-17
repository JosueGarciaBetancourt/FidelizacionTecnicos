<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesCanjeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Poblar EstadosCanje
        DB::table('EstadosCanje')->insertOrIgnore([
            ['idEstadoCanje' => 1, 'descripcion' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['idEstadoCanje' => 2, 'descripcion' => 'aprobado', 'created_at' => now(), 'updated_at' => now()],
            ['idEstadoCanje' => 3, 'descripcion' => 'rechazado', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Poblar SolicitudesCanje
        DB::table('SolicitudesCanje')->insertOrIgnore([
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idVentaIntermediada' => 'F001-00000072', // Verifica que exista en VentasIntermediadas
                'idTecnico' => '77043114',               // Verifica que exista en Tecnicos
                'idEstadoCanje' => 1,                    // Estado "pendiente"
                'fechaSolicitud' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idVentaIntermediada' => 'F001-00000073', // Verifica que exista en VentasIntermediadas
                'idTecnico' => '77665544',               // Verifica que exista en Tecnicos
                'idEstadoCanje' => 2,                    // Estado "aprobado"
                'fechaSolicitud' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Poblar SolicitudCanjeRecompensas
        DB::table('SolicitudCanjeRecompensas')->insertOrIgnore([
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idRecompensa' => 'RECOM-001', // Verifica que exista en Recompensas
                'cantidad' => 2,
                'costoRecompensa' => 500.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idRecompensa' => 'RECOM-002', // Verifica que exista en Recompensas
                'cantidad' => 1,
                'costoRecompensa' => 250.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idRecompensa' => 'RECOM-003', // Verifica que exista en Recompensas
                'cantidad' => 3,
                'costoRecompensa' => 750.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
