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
use App\Http\Controllers\OficioController;
use Illuminate\Support\Facades\Log;

Route::post('/log-error', function (Illuminate\Http\Request $request) {
    Log::error('Error en JavaScript: ' . $request->input('message'));
    return response()->json(['status' => 'error logged']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    //PDF 
    Route::get('/dashboard-canjes/historialCanje/pdf/{size}/{idCanje}', [CanjeController::class, 'canjePDF'])->name('canjes.pdf');

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
    Route::get('/dashboard-historial-canje', [CanjeController::class, 'historial'])->name('canjes.historial');  
    // Ver solicitudes (app móvil) 
    Route::get('/dashboard-solicitudesApp-canje', [CanjeController::class, 'solicitudesApp'])->name('canjes.solicitudesApp');  

    // Fetchs
    route::get('/dashboard-canjes/tecnico/{idTecnico}', [VentaIntermediadaController::class, 'getComprobantesEnEsperaByIdTecnico'])
        ->name('getVentasIntermediadasWithTecnico');
    route::get('/dashboard-canjes/historialCanje/{idCanje}', [CanjeController::class, 'getDetalleCanjesRecompensasByIdCanje'])
        ->name('getDetalleCanjesRecompensasByIdCanje');

    // Recompensas
    Route::get('/dashboard-recompensas', [RecompensaController::class, 'create'])->name('recompensas.create');  
    Route::post('/modal-storeRecompensa', [RecompensaController::class, 'store'])->name('recompensas.store');  
    Route::put('/modal-updateRecompensa', [RecompensaController::class, 'update'])->name('recompensas.update'); 
    Route::delete('/modal-deleteRecompensa', [RecompensaController::class, 'delete'])->name('recompensas.delete');
    Route::post('/modal-restoreRecompensa', [RecompensaController::class, 'restaurar'])->name('recompensas.restore');

    // Tipos recompensas
    Route::post('/modal-storeTipoRecompensa', [RecompensaController::class, 'storeTipoRecompensa'])->name('tiposRecompensas.store');  
    Route::put('/modal-updateTipoRecompensa', [RecompensaController::class, 'updateTipoRecompensa'])->name('tiposRecompensas.update'); 
    Route::delete('/modal-deleteTipoRecompensa', [RecompensaController::class, 'deleteTipoRecompensa'])->name('tiposRecompensas.delete');

    // Técnicos
    Route::get('/dashboard-tecnicos', [TecnicoController::class, 'create'])->name('tecnicos.create');  
    Route::post('/modal-storeTecnico', [TecnicoController::class, 'store'])->name('tecnicos.store');  
    Route::put('/modal-updateTecnico', [TecnicoController::class, 'update'])->name('tecnicos.update'); 
    Route::delete('/modal-deleteTecnico', [TecnicoController::class, 'delete'])->name('tecnicos.delete');
    Route::post('/modal-recontratarTecnico', [TecnicoController::class, 'recontratar'])->name('tecnicos.rehire'); 
    Route::get('/tblTecnicosData', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  

    // Oficios
    Route::get('/dashboard-oficios', [OficioController::class, 'create'])->name('oficios.create');  
    Route::post('/dashboard-storeOficios', [OficioController::class, 'store'])->name('oficios.store');  
    Route::put('/dashboard-updateOficios', [OficioController::class, 'update'])->name('oficios.update');  
    Route::delete('/dashboard-deleteOficios', [OficioController::class, 'delete'])->name('oficios.delete');  
    Route::post('/modal-restoreOficio', [OficioController::class, 'restaurar'])->name('oficios.restore');

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

