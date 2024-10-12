<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login_tecnicoController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

    //RUTAS API
    Route::post('/loginmovil/login-tecnicos', [Login_tecnicoController::class, 'login']);
    Route::get('/loginmovil/login-CelularTecnicos', [Login_tecnicoController::class, 'getAllCelularTecnicos']);
    Route::get('/getTecnico/{idTecnico}', [Login_tecnicoController::class, 'obtenerTecnicoPorId']);
    Route::get('/loginmovil/login-DataTecnicos', [Login_tecnicoController::class, 'getAllLoginTecnicos']);
    Route::get('/csrf-token', [Login_tecnicoController::class, 'getCsrfToken']);
    Route::get('/ventas-intermediadas/{idTecnico}', [Login_tecnicoController::class, 'getVentasIntermediadas']);
    Route::get('/recompensas', [Login_tecnicoController::class, 'obtenerRecompensas']);