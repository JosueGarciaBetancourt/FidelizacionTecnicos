<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VentaIntermediada;
use App\Http\Controllers\VentaIntermediadaController;

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
                'fechaHoraEmision_VentaIntermediada' => '2024-11-08 00:30:00',
                'montoTotal_VentaIntermediada' => 74.5,
                'puntosGanados_VentaIntermediada' => 75,
                'puntosActuales_VentaIntermediada'=> 0,
                'idEstadoVenta' => 3, // Redimido (completo)
            ],
            [
                'idVentaIntermediada' => 'F001-00000073',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García Betancourt',
                'tipoCodigoCliente_VentaIntermediada' => 'RUC',
                'codigoCliente_VentaIntermediada' => '10703047951',
                'nombreCliente_VentaIntermediada' => 'BERMUDEZ ROJAS MISHELL',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-09 08:25:11',
                'montoTotal_VentaIntermediada' => 400,
                'puntosGanados_VentaIntermediada' => 400,
                'puntosActuales_VentaIntermediada'=> 400,
                'idEstadoVenta' => 1, // En espera 
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
                'idEstadoVenta' => 4, // Tiempo agotado
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
                'idEstadoVenta' => 1, // En espera 
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
                'idEstadoVenta' => 2, // Redimido (parcial) 
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
                'idEstadoVenta' => 3,  // Redimido (completo)
            ],
            [
                // Está venta intermediada está cerca de cumplir 90 días para pasar a tener un estado de Tiempo Agotado
                'idVentaIntermediada' => 'F001-00000999',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '45404787',
                'nombreCliente_VentaIntermediada' => 'BAQUERIZO QUISPE, ELIZABETH SILVIA',
                'fechaHoraEmision_VentaIntermediada' => '2024-08-30 10:00:00', // 90 días hasta el 2024-11-20
                'fechaHoraCargada_VentaIntermediada' => '2024-08-20 10:00:00',
                'montoTotal_VentaIntermediada' => 100,
                'puntosGanados_VentaIntermediada' => 100,
                'puntosActuales_VentaIntermediada' => 100,
                'idEstadoVenta' => 1,  // En Espera
            ],

            /*Ventas para las solicitudes canje*/
            /*PENDIENTES*/
            [
                'idVentaIntermediada' => 'F001-00000444',
                'idTecnico' => '77043114',
                'nombreTecnico' => 'Josué García',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '45404787',
                'nombreCliente_VentaIntermediada' => 'BAQUERIZO QUISPE, ELIZABETH SILVIA',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-30 10:00:00', 
                'fechaHoraCargada_VentaIntermediada' => '2024-11-20 10:00:00',
                'montoTotal_VentaIntermediada' => 500,
                'puntosGanados_VentaIntermediada' => 500,
                'puntosActuales_VentaIntermediada' => 500,
                'idEstadoVenta' => 1,  // En Espera
            ],
            [
                'idVentaIntermediada' => 'F001-00000555',
                'idTecnico' => '77665544',
                'nombreTecnico' => 'Manuel Carrasco',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '45404787',
                'nombreCliente_VentaIntermediada' => 'BAQUERIZO QUISPE, ELIZABETH SILVIA',
                'fechaHoraEmision_VentaIntermediada' => '2024-10-30 10:00:00', 
                'fechaHoraCargada_VentaIntermediada' => '2024-11-20 10:00:00',
                'montoTotal_VentaIntermediada' => 1000,
                'puntosGanados_VentaIntermediada' => 1000,
                'puntosActuales_VentaIntermediada' => 1000,
                'idEstadoVenta' => 1,  // En Espera
            ],
            [
                'idVentaIntermediada' => 'F001-00001234',
                'idTecnico' => '43111949',
                'nombreTecnico' => 'Técnico Guillermo Peña',
                'tipoCodigoCliente_VentaIntermediada' => 'DNI',
                'codigoCliente_VentaIntermediada' => '11223344',
                'nombreCliente_VentaIntermediada' => 'Cliente de prueba',
                'fechaHoraEmision_VentaIntermediada' => '2024-11-30 10:00:00', 
                'fechaHoraCargada_VentaIntermediada' => '2024-11-30 10:30:00',
                'montoTotal_VentaIntermediada' => 999.99,
                'puntosGanados_VentaIntermediada' => 1000,
                'puntosActuales_VentaIntermediada' => 1000,
                'idEstadoVenta' => 1,  // En Espera
            ],
        ];

        foreach ($ventasIntermediadas as $ventaData) {
            // Verifica si ya tiene una fecha `fechaHoraCargada_VentaIntermediada`.
            if (!isset($ventaData['fechaHoraCargada_VentaIntermediada'])) {
                $ventaData['fechaHoraCargada_VentaIntermediada'] = now();
            }

            // Crea la venta intermediada
            $venta = VentaIntermediada::create($ventaData);

            // Actualiza el estado con la lógica del controlador
            $nuevoEstado = VentaIntermediadaController::returnStateIdVentaIntermediada(
                                                            $venta->idVentaIntermediada,
                                                            $venta->puntosActuales_VentaIntermediada,
                                                         );
            // Aplica el nuevo estado al registro
            $venta->update(['idEstadoVenta' => $nuevoEstado]);
        }
    }
}
