<?php

use App\Mail\ResetPasswordMail;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\OficioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecompensaController;
use App\Http\Controllers\SolicitudCanjeController;
use App\Http\Controllers\VentaIntermediadaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('', [AuthenticatedSessionController::class, 'create']);

Route::middleware(['auth', 'verified'])->group(function () {
    //PDF 
    Route::get('/dashboard-canjes/historialCanje/pdf/{size}/{idCanje}', [CanjeController::class, 'canjePDF'])->name('canjes.pdf');

    // Perfil (laravel)
    /* Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');*/

    // Perfil
    Route::get('/dashboard-usuarios', [ProfileController::class, 'create'])->name('usuarios.create');
    Route::post('/dashboard-storeUsuario', [RegisteredUserController::class, 'store'])->name('usuarios.store');
    Route::patch('/dashboard-updateUsuario', [ProfileController::class, 'update'])->name('usuarios.update');
    Route::post('/dashboard-enableUsuario/{idUsuario}', [ProfileController::class, 'enable'])->name('usuarios.enable');
    Route::delete('/dashboard-disableUsuario/{idUsuario}', [ProfileController::class, 'disable'])->name('usuarios.disable');
    Route::delete('/dashboard-deleteUsuario/{idUsuario}', [ProfileController::class, 'delete'])->name('usuarios.delete');

    // Ventas Intermediadas
    Route::get('/dashboard-ventasIntermediadas', [VentaIntermediadaController::class, 'create'])->name('ventasIntermediadas.create');
    Route::post('/modal-storeVenta', [VentaIntermediadaController::class, 'store'])->name('ventasIntermediadas.store');
    Route::delete('/modal-deleteVenta', [VentaIntermediadaController::class, 'delete'])->name('ventasIntermediadas.delete');
    route::get('/dashboard-canjes/tecnico/{idTecnico}', [VentaIntermediadaController::class, 'getComprobantesEnEsperaByIdTecnico'])
            ->name('getVentasIntermediadasWithTecnico');

    // Canjes
    // Registrar 
    Route::get('/dashboard-registrarCanje', [CanjeController::class, 'registrar'])->name('canjes.registrar');  
    Route::post('/dashboard-registrarCanje-storeCanje', [CanjeController::class, 'store'])->name('canjes.store');  
    // Ver historial 
    Route::get('/dashboard-historial-canje', [CanjeController::class, 'historial'])->name('canjes.historial');  
    route::get('/dashboard-canjes/historialCanje/{idCanje}', [CanjeController::class, 'getDetalleCanjesRecompensasByIdCanje'])
            ->name('getDetalleCanjesRecompensasByIdCanje');
    
    // Solicitudes Canjes
    Route::get('/dashboard-solicitudesApp-canje', [SolicitudCanjeController::class, 'create'])->name('solicitudescanjes.create');  
    route::get('/dashboard-canjes/solicitudCanje/{idSolicitudCanje}', [SolicitudCanjeController::class, 'getDetalleSolicitudesCanjesRecompensasByIdSolicitudCanje'])
            ->name('getDetalleSolicitudesCanjesRecompensasByIdSolicitudCanje');
    Route::post('/dashboard-canjes/solicitudCanje/aprobar/{idSolicitudCanje}', [SolicitudCanjeController::class, 'aprobarSolicitudCanje'])
            ->name('aprobarSolicitudCanje');
    route::post('/dashboard-canjes/solicitudCanje/rechazar/{idSolicitudCanje}', [SolicitudCanjeController::class, 'rechazarSolicitudCanje'])
            ->name('rechazarSolicitudCanje');

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
    //Route::post('/tblTecnicosData', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  
    Route::get('/tblTecnicosData', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  

    // Oficios
    Route::get('/dashboard-oficios', [OficioController::class, 'create'])->name('oficios.create');  
    Route::post('/dashboard-storeOficios', [OficioController::class, 'store'])->name('oficios.store');  
    Route::put('/dashboard-updateOficios', [OficioController::class, 'update'])->name('oficios.update');  
    Route::delete('/dashboard-deleteOficios', [OficioController::class, 'delete'])->name('oficios.delete');  
    Route::post('/modal-restoreOficio', [OficioController::class, 'restaurar'])->name('oficios.restore');
    Route::get('/tblOficiosData', [OficioController::class, 'tabla'])->name('oficios.tabla');

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

