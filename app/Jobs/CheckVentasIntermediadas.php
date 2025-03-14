<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use App\Models\VentaIntermediada;
use App\Models\TecnicoNotification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckVentasIntermediadas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Controller::printJSON(config('settings'));

        // Obtener valores desde la configuración
        $maxDaysCanje = config('settings.maxdaysCanje');
        $diasAgotarVentaIntermediadaNotificacion = config('settings.diasAgotarVentaIntermediadaNotificacion');
        $maxDaysNotification = $maxDaysCanje - $diasAgotarVentaIntermediadaNotificacion;

        // Obtener ventas dentro del rango permitido
        $ventas = VentaIntermediada::whereIn('idEstadoVenta', [1, 2, 4, 5])
            ->where('diasTranscurridos', '<=', $maxDaysNotification)
            ->get();

        if ($ventas->isNotEmpty()) {
            foreach ($ventas as $venta) {
                $remainingDays = config('settings.maxdayscanje') - $venta->diasTranscurridos;
                TecnicoNotification::create([
                    "idTecnico" => $venta->idTecnico,
                    "idVentaIntermediada" => $venta->idVentaIntermediada,
                    "description" => "La venta intermediada " .  $venta->idVentaIntermediada . "se agotará en " . $remainingDays . " días",
                ]);
            }
        }
    }
}

