<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

abstract class Controller
{
    // Función general para calcular los días transcurridos hasta el día de hoy
    public function returnDiasTranscurridosHastaHoy($fecha) {
        $fechaObj = Carbon::parse($fecha);
        $dias = (int) $fechaObj->diffInDays(Carbon::now());
        return $dias;
    }
}
