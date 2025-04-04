<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SolicitudCanjeController;
use Illuminate\Http\Request;

class CrearSolicitudCanje extends Command
{
    protected $signature = 'solicitud:crear {idVenta} {idTecnico}';
    protected $description = 'Crea una solicitud de canje con los datos proporcionados';

    public function handle()
    {
        $idVenta = $this->argument('idVenta');
        $idTecnico = $this->argument('idTecnico');

        // Simular los datos de entrada
        $request = new Request([
            'idVentaIntermediada' => $idVenta,
            'idTecnico' => $idTecnico,
            'recompensas' => [
                [
                    'idRecompensa' => 'RECOM-001', // Pulsera de silicona
                    'cantidad' => 2,
                    'costoRecompensa' => 1
                ]
            ],
            'puntosCanjeados_SolicitudCanje' => 2
        ]);

        $controller = new SolicitudCanjeController();
        $response = $controller->crearSolicitud($request);

        $this->info('Solicitud creada: ' . json_encode($response->getData(), JSON_PRETTY_PRINT));
    }
}
