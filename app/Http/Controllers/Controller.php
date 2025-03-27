<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

abstract class Controller
{   
    public static bool $newNotifications = false;

    // Función general para calcular los días transcurridos hasta el día de hoy considerando horas
    public static function returnDiasTranscurridosHastaHoy($fechaHora) {
        try {
            // Obtener la zona horaria desde el archivo .env
            $zonaHoraria = env('APP_TIMEZONE', 'UTC');
    
            // Convertir la fecha ingresada y la actual en objetos Carbon
            $fechaObj = Carbon::parse($fechaHora, $zonaHoraria);
            $ahora = Carbon::now($zonaHoraria);
    
            // Calcular la diferencia en horas totales
            $horasTotales = $fechaObj->diffInHours($ahora, false);
            
            // Convertir a días enteros (división entera)
            $diasTranscurridos = (int)($horasTotales / 24);
            
            return $diasTranscurridos;
            
        } catch (\Exception $e) {
            Log::error("Error al calcular días transcurridos: " . $e->getMessage());
            return 0;
        }
    }

    public static function printJSON($data) {
        Log::info(json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerFechaHoraFormateadaExportaciones() {
        $fecha = new DateTime(); // Obtiene la fecha y hora actual
        return $fecha->format('dmY'); // Formato día (2 dígitos), mes (2 dígitos), año (4 dígitos)
    }
}
