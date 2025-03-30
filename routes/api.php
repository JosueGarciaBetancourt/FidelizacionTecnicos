<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login_tecnicoController;
use App\Http\Controllers\SolicitudCanjeController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

    //RUTAS API
    Route::post('/loginmovil/login-tecnicos', [Login_tecnicoController::class, 'login']);


    
    Route::get('/getTecnico/{idTecnico}', [Login_tecnicoController::class, 'obtenerTecnicoPorId']);  
    Route::get('/ventasIntermediadasSolicitud/{idTecnico}', [Login_tecnicoController::class, 'getVentasIntermediadasFiltradas']);
    Route::get('/ventas-intermediadas/{idTecnico}', [Login_tecnicoController::class, 'getVentasIntermediadas']);
    Route::get('/recompensas', [Login_tecnicoController::class, 'obtenerRecompensas']);
    Route::get('/notificaciones/{idTecnico}', [Login_tecnicoController::class, 'getNotificacionesTecnico']);
    Route::get('/oficios', [Login_tecnicoController::class, 'getAvailableJobs']);
    Route::post('/cambiar-password', [Login_tecnicoController::class, 'changePassword']);
    Route::put('/tecnico/{idTecnico}/oficios', [Login_tecnicoController::class, 'changeJobs']);
    Route::get('/oficiosTecnicos', [Login_tecnicoController::class, 'getAllFullModelsTecnicos']);

    //RUTAS SOLICITUDES
    Route::post('/solicitudes/canje', [SolicitudCanjeController::class, 'crearSolicitud']);
    Route::get('/solicitudes-canje/{idTecnico}', [SolicitudCanjeController::class, 'getSolicitudesPorTecnico']); // Listar todas las solicitudes de canje del técnico
    Route::get('/solicitudes-canje/{idSolicitudCanje}/detalles', [SolicitudCanjeController::class, 'getDetallesSolicitud']); // Ver detalles de una solicitud de canje
    Route::delete('/solicitudes-canje/delete/{idSolicitudCanje}', [SolicitudCanjeController::class, 'eliminarSolicitud']);

    
    Route::middleware('auth.api')->group(function () {
        

    });
