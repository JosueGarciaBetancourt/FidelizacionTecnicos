<?php

use App\Mail\ResetPasswordMail;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecompensaController;
use App\Http\Controllers\VentaIntermediadaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ventas Intermediadas
    Route::get('/dashboard-ventasIntermediadas', [VentaIntermediadaController::class, 'create'])->name('ventasIntermediadas.create');
    Route::post('/modal-storeVenta', [VentaIntermediadaController::class, 'store'])->name('ventasIntermediadas.store');
    
    // Canjes
    // Registrar 
    Route::get('/dashboard-registrarCanje', [CanjeController::class, 'create'])->name('canjes.create');  
    Route::post('/dashboard-registrarCanje-storeCanje', [CanjeController::class, 'store'])->name('canjes.store');  
    // Ver historial 
    Route::get('/dashboard-historial-canje', [CanjeController::class, 'create'])->name('canjes.historial');  
    // Ver solicitudes (app móvil) 
    Route::get('/dashboard-solicitudesApp-canje', [CanjeController::class, 'create'])->name('canjes.solicitudesApp');  

    // Fetch
    route::get('/dashboard-canjes/tecnico/{idTecnico}', [VentaIntermediadaController::class, 'getComprobantesEnEsperaByIdTecnico'])
        ->name('getVentasIntermediadasWithTecnico');

    // Recompensas
    Route::get('/dashboard-recompensas', [RecompensaController::class, 'create'])->name('recompensas.create');  
    Route::post('/modal-storeRecompensa', [RecompensaController::class, 'store'])->name('recompensas.store');  
    Route::put('/modal-updateRecompensa', [RecompensaController::class, 'update'])->name('recompensas.update'); 
    Route::delete('/modal-deleteRecompensa', [RecompensaController::class, 'delete'])->name('recompensas.delete');

    // Técnicos
    Route::get('/dashboard-tecnicos', [TecnicoController::class, 'create'])->name('tecnicos.create');  
    Route::post('/modal-storeTecnico', [TecnicoController::class, 'store'])->name('tecnicos.store');  
    Route::put('/modal-updateTecnico', [TecnicoController::class, 'update'])->name('tecnicos.update'); 
    Route::delete('/modal-deleteTecnico', [TecnicoController::class, 'delete'])->name('tecnicos.delete');
    Route::get('/tblTecnicosData', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  

    // Configuración
    Route::get('/dashboard-configuracion', [DashboardController::class, 'configuracion'])->name('configuracion');  

    // Correos
    Route::get('emailExample', function () {
        Mail::to('garciabetancourtjosue@gmail.com')
            ->send(new ResetPasswordMail);
        return "Mensaje enviado";
    })->name('emailExample');
});

require __DIR__.'/auth.php';

