<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VentaIntermediada;

class VentaIntermediadaSeeder extends Seeder
{
    public function run(): void
    {
        $ventasIntermediadas = [
            [
                'idVentaIntermediada' => 'F001-00000072',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10422733669',
                'nombreCliente_VentaIntermediada' => 'AQUINO LOPEZ EMERSON',
                'fechaHoraEmision_VentaIntermediada' => '2024-04-30 08:25:11',
                'montoTotal_VentaIntermediada' => 74.5,
                'puntosGanados_VentaIntermediada' => 75,
                'puntosActuales_VentaIntermediada'=> 0,
                'idEstadoVenta' => 3,
            ],
            [
                'idVentaIntermediada' => 'F001-00000073',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10703047951',
                'nombreCliente_VentaIntermediada' => 'BERMUDEZ ROJAS MISHELL',
                'fechaHoraEmision_VentaIntermediada' => '2024-05-30 08:25:11',
                'montoTotal_VentaIntermediada' => 400,
                'puntosGanados_VentaIntermediada' => 400,
                'puntosActuales_VentaIntermediada'=> 400,
                'idEstadoVenta' => 1,
            ],
            [
                'idVentaIntermediada' => 'F001-00000074',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '47982407',
                'nombreCliente_VentaIntermediada' => 'VENTURA ALMONACID, NILTON',
                'fechaHoraEmision_VentaIntermediada' => '2024-06-30 08:25:11',
                'montoTotal_VentaIntermediada' => 125,
                'puntosGanados_VentaIntermediada' => 125,
                'puntosActuales_VentaIntermediada'=> 125,
                'idEstadoVenta' => 4,
            ],
            [
                'idVentaIntermediada' => 'F001-00000075',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10456418771',
                'nombreCliente_VentaIntermediada' => 'PEREZ VIDALON LUIS EDGAR',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-10 10:00:00',
                'fechaHoraCargada_VentaIntermediada' => '2024-10-25 10:00:00', 
                'montoTotal_VentaIntermediada' => 99.99,
                'puntosGanados_VentaIntermediada' => 100,
                'puntosActuales_VentaIntermediada' => 100,
                'idEstadoVenta' => 1,
            ],
            [
                'idVentaIntermediada' => 'F001-00000076',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '72385453',
                'nombreCliente_VentaIntermediada' => 'OSORIO VILLAFUERTE, JOSE LUIS',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 08:25:11',
                'montoTotal_VentaIntermediada' => 450,
                'puntosGanados_VentaIntermediada' => 450,
                'puntosActuales_VentaIntermediada' => 400,
                'idEstadoVenta' => 2,
            ],
            [
                // Está venta intermediada ha sido canjeada en dos partes (más detalle en CanjeSeeder.php)
                'idVentaIntermediada' => 'F001-00000077',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '45404787',
                'nombreCliente_VentaIntermediada' => 'BAQUERIZO QUISPE, ELIZABETH SILVIA',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-20 10:00:00',
                'fechaHoraCargada_VentaIntermediada' => '2024-10-25 10:00:00',
                'montoTotal_VentaIntermediada' => 450,
                'puntosGanados_VentaIntermediada' => 450,
                'puntosActuales_VentaIntermediada' => 0,
                'idEstadoVenta' => 3,
            ],
        ];

        foreach ($ventasIntermediadas as $venta) {
            $venta['fechaHoraCargada_VentaIntermediada'] = now();
            VentaIntermediada::create($venta);
        }
    }
}
