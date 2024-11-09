<?php

namespace App\Http\Controllers;

use App\Models\Canje;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\CanjeRecompensa;

class CanjeRecompensaController extends Controller
{
    public static function updateCanjeRecompensa($idCanje, $idRecompensa, $cantidad) {
        $recompensa = Recompensa::findOrFail($idRecompensa);
        $costoRecompensa = $recompensa->costoPuntos_Recompensa;
        $canjeRecompensa = CanjeRecompensa::create([
            'idCanje' => $idCanje,
            'idRecompensa' => $idRecompensa,
            'cantidad' => $cantidad,
            'costoRecompensa' => $costoRecompensa,
        ]);
    }    
}
