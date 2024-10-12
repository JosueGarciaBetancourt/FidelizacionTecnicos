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
                'estadoVentaIntermediada' => 'Redimido',
            ],
            [
                'idVentaIntermediada' => 'F001-00000073',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10703047951',
                'nombreCliente_VentaIntermediada' => 'BERMUDEZ ROJAS MISHELL',
                'fechaHoraEmision_VentaIntermediada' => '2024-05-30 08:25:11',
                'montoTotal_VentaIntermediada' => 20,
                'puntosGanados_VentaIntermediada' => 20,
                'estadoVentaIntermediada' => 'En espera',
            ],
            [
                'idVentaIntermediada' => 'F001-00000074',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '47982407',
                'nombreCliente_VentaIntermediada' => 'VENTURA ALMONACID, NILTON',
                'fechaHoraEmision_VentaIntermediada' => '2024-06-30 08:25:11',
                'montoTotal_VentaIntermediada' => 120,
                'puntosGanados_VentaIntermediada' => 120,
                'estadoVentaIntermediada' => 'Tiempo agotado',
            ],
            [
                'idVentaIntermediada' => 'F001-00000075',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10456418771',
                'nombreCliente_VentaIntermediada' => 'PEREZ VIDALON LUIS EDGAR',
                'fechaHoraEmision_VentaIntermediada' => '2024-04-30 08:25:11',
                'montoTotal_VentaIntermediada' => 74.5,
                'puntosGanados_VentaIntermediada' => 75,
                'estadoVentaIntermediada' => 'Redimido',
            ],
            [
                'idVentaIntermediada' => 'F001-00000076',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '72385453',
                'nombreCliente_VentaIntermediada' => 'OSORIO VILLAFUERTE, JOSE LUIS',
                'fechaHoraEmision_VentaIntermediada' => '2024-05-30 08:25:11',
                'montoTotal_VentaIntermediada' => 20,
                'puntosGanados_VentaIntermediada' => 20,
                'estadoVentaIntermediada' => 'En espera',
            ],
            [
                'idVentaIntermediada' => 'F001-00000077',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '45404787',
                'nombreCliente_VentaIntermediada' => 'BAQUERIZO QUISPE, ELIZABETH SILVIA',
                'fechaHoraEmision_VentaIntermediada' => '2024-06-30 08:25:11',
                'montoTotal_VentaIntermediada' => 120,
                'puntosGanados_VentaIntermediada' => 120,
                'estadoVentaIntermediada' => 'Tiempo agotado',
            ],
        ];

        foreach ($ventasIntermediadas as $venta) {
            $venta['fechaHoraCargada_VentaIntermediada'] = now();
            VentaIntermediada::create($venta);
        }
    }
}
