<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use App\Models\VentaIntermediada;
use App\Models\TecnicoNotification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckVentasIntermediadas
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            Log::info("✅ Ejecutando job CheckVentasIntermediadas...");

            $maxDaysCanje = config('settings.maxdaysCanje');
            $diasAgotarVentaIntermediadaNotificacion = config('settings.diasAgotarVentaIntermediadaNotificacion');
            $maxDaysNotification = $maxDaysCanje - $diasAgotarVentaIntermediadaNotificacion;

            /*  Log::info('Configuración:', [
                'maxDaysCanje' => $maxDaysCanje,
                'diasAgotarVentaIntermediadaNotificacion' => $diasAgotarVentaIntermediadaNotificacion,
                'maxDaysNotification' => $maxDaysNotification
            ]); */

            $ventas = VentaIntermediada::whereIn('idEstadoVenta', [1, 2, 4, 5])->get()
                ->filter(function ($venta) use ($maxDaysNotification, $maxDaysCanje) {
                    return $venta->diasTranscurridos <= $maxDaysCanje && $venta->diasTranscurridos >= $maxDaysNotification;
                });

            if ($ventas->isNotEmpty()) {
                foreach ($ventas as $venta) {
                    $remainingDays = $maxDaysCanje - $venta->diasTranscurridos;

                    TecnicoNotification::create([
                        "idTecnico" => $venta->idTecnico,
                        "idVentaIntermediada" => $venta->idVentaIntermediada,
                        "description" => "La venta intermediada " .  $venta->idVentaIntermediada . " se agotará en " . $remainingDays . " días",
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);

                    Log::info("🔔 Notificación creada para la venta intermediada {$venta->idVentaIntermediada}");
                }
            } else {
                Log::info("🟡 No se encontraron ventas intermediadas para notificar.");
            }
        } catch (\Exception $e) {
            Log::error("❌ Error en job CheckVentasIntermediadas: " . $e->getMessage());
        }
    }
}

