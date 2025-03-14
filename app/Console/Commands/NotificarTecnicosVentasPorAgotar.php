<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VentaIntermediada;
use App\Models\TecnicoNotification;
use Carbon\Carbon;

class NotificarTecnicosVentasPorAgotar extends Command
{
    protected $signature = 'app:notificar-tecnicos-ventas-por-agotar';
    protected $description = 'Command to notify technicians that their sales are about to run out';

    public function handle()
    {
        // Obtener todas las ventas intermediadas con sus días restantes para agotarse
        $ventas = VentaIntermediada::all()->filter(function ($venta) {
            return config('settings.maxdaysCanje') - $venta['diasTranscurridos']; // Remaining days
        });

        $contador = 0;
        foreach ($ventas as $venta) {
            // Verificar si ya existe una notificación para evitar duplicados
            $existeNotificacion = TecnicoNotification::where('idTecnico', $venta->idTecnico)
                ->where('idVentaIntermediada', $venta->idVentaIntermediada)
                ->exists();

            if (!$existeNotificacion) {
                TecnicoNotification::create([
                    'idTecnico' => $venta->idTecnico,
                    'idVentaIntermediada' => $venta->idVentaIntermediada,
                    'description' => "La venta {$venta->idVentaIntermediada} ha alcanzado 7 días.",
                    'active' => true
                ]);
                $contador++;
            }
        }

        $this->info("Se han registrado {$contador} notificaciones.");
    }
}
