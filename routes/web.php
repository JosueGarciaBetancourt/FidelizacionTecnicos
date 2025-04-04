<?php

use App\Mail\ResetPasswordMail;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\RangoController;
use App\Http\Controllers\OficioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecompensaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\SolicitudCanjeController;
use App\Http\Controllers\TipoRecompensaController;
use App\Http\Controllers\VentaIntermediadaController;
use App\Http\Controllers\SystemNotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('', [AuthenticatedSessionController::class, 'create']);

Route::middleware(['auth', 'verified'])->group(function () {
    //PDF
    Route::get('/dashboard-canjes/historialCanje/pdf/{size}/{idCanje}', [CanjeController::class, 'canjePDF'])->name('canjes.pdf');


    // Perfil de usuario
    Route::get('/dashboard-usuarios', [ProfileController::class, 'create'])->name('usuarios.create');
    Route::post('/dashboard-storeUsuario', [RegisteredUserController::class, 'store'])->name('usuarios.store');
    Route::patch('/dashboard-updateUsuario', [ProfileController::class, 'update'])->name('usuarios.update');
    Route::post('/dashboard-enableUsuario/{idUsuario}', [ProfileController::class, 'enableUser'])->name('usuarios.enable');
    Route::delete('/dashboard-disableUsuario/{idUsuario}', [ProfileController::class, 'disableUser'])->name('usuarios.disable');
    Route::delete('/dashboard-deleteUsuario/{idUsuario}', [ProfileController::class, 'deleteUser'])->name('usuarios.delete');
    Route::get('/dashboard-getLoggedUser', [ProfileController::class, 'getLoggedUser'])->name('usuarios.getLoggedUser');
    Route::post('/verificar-userDataDuplication', [ProfileController::class, 'verifyUserDataDuplication'])->name('usuarios.verifyUserDataDuplication');
    Route::post('/verificar-userEditDataDuplication', [ProfileController::class, 'verifyUserEditDataDuplication'])->name('usuarios.verifyUserEditDataDuplication');
    Route::get('/dashboard-getAdminEmail', [ProfileController::class, 'getAdminEmail'])->name('usuarios.getAdminEmail');
    Route::get('/dashboard-getEmailDomain', [ProfileController::class, 'getEmailDomain'])->name('usuarios.getEmailDomain');
    Route::get('/dashboard-getMaxdaysCanje', [ProfileController::class, 'getMaxdaysCanje'])->name('usuarios.getMaxdaysCanje');
    Route::get('/dashboard-getdiasAgotarVentaIntermediadaNotificacion', [ProfileController::class, 'getdiasAgotarVentaIntermediadaNotificacion'])
        ->name('usuarios.getdiasAgotarVentaIntermediadaNotificacion');


    // Ventas Intermediadas
    Route::get('/dashboard-ventasIntermediadas', [VentaIntermediadaController::class, 'create'])->name('ventasIntermediadas.create');
    Route::post('/modal-storeVenta', [VentaIntermediadaController::class, 'store'])->name('ventasIntermediadas.store');
    Route::put('/modal-updateMaxdayscanje', [VentaIntermediadaController::class, 'updateMaxdayscanje'])->name('ventasIntermediadas.updateMaxdayscanje');
    Route::delete('/modal-deleteVenta', [VentaIntermediadaController::class, 'delete'])->name('ventasIntermediadas.delete');
    Route::post('/tblVentasIntermediadasData', [VentaIntermediadaController::class, 'tabla'])->name('ventasIntermediadas.tabla');  
    Route::post('/dashboard-ventasIntermediadas/getVentaByIdVentaIdNombreTecnico', [VentaIntermediadaController::class, 'getVentaByIdVentaIdNombreTecnico'])
        ->name('ventasIntermediadas.getVentaByIdVentaIdNombreTecnico');
    Route::get('/dashboard-ventasIntermediadas/getPaginatedVentas', [VentaIntermediadaController::class, 'getPaginatedVentas'])
        ->name('ventasIntermediadas.paginated');  
    Route::post('/dashboard-ventasIntermediadas/getFilteredVentas', [VentaIntermediadaController::class, 'getFilteredVentas'])->name('ventasIntermediadas.filter');  
    Route::get('/dashboard-canjes/tecnico/{idTecnico}', [VentaIntermediadaController::class, 'getComprobantesEnEsperaByIdTecnico'])
        ->name('getVentasIntermediadasWithTecnico');
    Route::post('/verificar-venta', [VentaIntermediadaController::class, 'verificarExistenciaVenta'])->name('ventasIntermediadas.verificarExistencia');
    Route::get('/dashboard-ventasIntermediadas/export-pdf', [VentaIntermediadaController::class, 'exportarAllVentasPDF'])->name('ventasIntermediadas.tablaPDF');


    // Canjes
    // Registrar 
    Route::get('/dashboard-registrarCanje', [CanjeController::class, 'registrar'])->name('canjes.registrar');  
    Route::post('/dashboard-registrarCanje-storeCanje', [CanjeController::class, 'store'])->name('canjes.store');  
    // Ver historial 
    Route::get('/dashboard-historial-canje', [CanjeController::class, 'historial'])->name('canjes.historial');  
    Route::get('/dashboard-canjes/canjeAndDetails/{idCanje}', [CanjeController::class, 'getObjCanjeAndDetailsByIdCanje'])
        ->name('getObjCanjeAndDetailsByIdCanje');
    Route::post('/tblHistorialCanjesData', [CanjeController::class, 'tablaHistorialCanje'])->name('canjes.tablaHistorialCanje');  
    Route::get('/dashboard-canjes/export-pdf', [CanjeController::class, 'exportarAllCanjesPDF'])->name('canjes.tablaPDF');


    // Solicitudes Canjes
    Route::get('/dashboard-solicitudesApp-canje', [SolicitudCanjeController::class, 'create'])->name('solicitudescanjes.create');  
    Route::post('/tablaSolicitudCanjeData', [SolicitudCanjeController::class, 'tablaSolicitudCanje'])->name('solicitudescanjes.tablaSolicitudCanje');  
    Route::get('/dashboard-solicitudesCanjes/solicitudCanjeAndDetails/{idSolicitudCanje}', [SolicitudCanjeController::class, 'getObjSolicitudCanjeAndDetailsByIdSolicitudCanje'])
            ->name('getObjSolicitudCanjeAndDetailsByIdSolicitudCanje');
    Route::post('/dashboard-solicitudesCanjes/solicitudCanje/aprobar/{idSolicitudCanje}', [SolicitudCanjeController::class, 'aprobarSolicitudCanje'])
            ->name('aprobarSolicitudCanje');
    Route::post('/dashboard-solicitudesCanjes/solicitudCanje/rechazar/{idSolicitudCanje}', [SolicitudCanjeController::class, 'rechazarSolicitudCanje'])
            ->name('rechazarSolicitudCanje');
    Route::get('/dashboard-solicitudesCanjes/export-pdf', [SolicitudCanjeController::class, 'exportarAllSolicitudesCanjesPDF'])->name('solicitudescanjes.tablaPDF');


    // Recompensas
    Route::get('/dashboard-recompensas', [RecompensaController::class, 'create'])->name('recompensas.create');  
    Route::post('/modal-storeRecompensa', [RecompensaController::class, 'store'])->name('recompensas.store');  
    Route::put('/modal-updateRecompensa', [RecompensaController::class, 'update'])->name('recompensas.update'); 
    Route::delete('/modal-disableRecompensa', [RecompensaController::class, 'disable'])->name('recompensas.disable');
    Route::post('/modal-restoreRecompensa', [RecompensaController::class, 'restore'])->name('recompensas.restore');
    Route::delete('/modal-deleteRecompensa', [RecompensaController::class, 'delete'])->name('recompensas.delete');
    Route::get('/dashboard-recompensas/export-pdf', [RecompensaController::class, 'exportarAllRecompensasPDF'])->name('recompensas.tablaPDF');


    // Tipos recompensas
    Route::get('/dashboard-tiposRecompensas', [TipoRecompensaController::class, 'create'])->name('tiposRecompensas.create');  
    Route::post('/modal-storeTipoRecompensa', [TipoRecompensaController::class, 'store'])->name('tiposRecompensas.store');  
    Route::put('/modal-updateTipoRecompensa', [TipoRecompensaController::class, 'update'])->name('tiposRecompensas.update'); 
    Route::delete('/modal-disableTipoRecompensa', [TipoRecompensaController::class, 'disable'])->name('tiposRecompensas.disable'); 
    Route::post('/modal-restoreTipoRecompensa', [TipoRecompensaController::class, 'restore'])->name('tiposRecompensas.restore'); 
    Route::delete('/modal-deleteTipoRecompensa', [TipoRecompensaController::class, 'delete'])->name('tiposRecompensas.delete');
    Route::get('/dashboard-tiposRecompensas/export-pdf', [TipoRecompensaController::class, 'exportarAllTiposRecompensasPDF'])->name('tiposRecompensas.tablaPDF');


    // Técnicos
    Route::get('/dashboard-tecnicos', [TecnicoController::class, 'create'])->name('tecnicos.create');  
    Route::post('/modal-storeTecnico', [TecnicoController::class, 'store'])->name('tecnicos.store');  
    Route::put('/modal-updateTecnico', [TecnicoController::class, 'update'])->name('tecnicos.update'); 
    Route::delete('/modal-disableTecnico', [TecnicoController::class, 'disable'])->name('tecnicos.disable');
    Route::post('/modal-restoreTecnico', [TecnicoController::class, 'restore'])->name('tecnicos.restore');
    Route::delete('/modal-deleteTecnico', [TecnicoController::class, 'delete'])->name('tecnicos.delete');
    Route::get('/dashboard-tecnicos/getPaginatedTecnicos', [TecnicoController::class, 'getPaginatedTecnicos'])->name('tecnicos.paginated');  
    Route::post('/dashboard-tecnicos/getFilteredTecnicos', [TecnicoController::class, 'getFilteredTecnicos'])->name('tecnicos.filter');  
    Route::post('/tblTecnicosData', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  
    Route::post('/verificar-tecnico', [TecnicoController::class, 'verificarExistenciaTecnico'])->name('tecnicos.verificarExistencia');
    Route::post('/dashboard-tecnicos/getTecnicoByIdNombre', [TecnicoController::class, 'getTecnicoByIdNombre'])
                ->name('tecnicos.getTecnicoByIdFetch');
    Route::post('/dashboard-tecnicos/restorePassword', [TecnicoController::class, 'restorePassword'])->name('tecnicos.restorePassword');
    Route::get('/dashboard-tecnicos/export-pdf', [TecnicoController::class, 'exportarAllTecnicosPDF'])->name('tecnicos.tablaPDF');


    // Oficios
    Route::get('/dashboard-oficios', [OficioController::class, 'create'])->name('oficios.create');  
    Route::post('/dashboard-storeOficios', [OficioController::class, 'store'])->name('oficios.store');  
    Route::put('/dashboard-updateOficios', [OficioController::class, 'update'])->name('oficios.update');  
    Route::delete('/dashboard-disableOficios', [OficioController::class, 'disable'])->name('oficios.disable');  
    Route::post('/modal-restoreOficio', [OficioController::class, 'restore'])->name('oficios.restore');
    Route::delete('/dashboard-deleteOficios', [OficioController::class, 'delete'])->name('oficios.delete');  
    Route::get('/tblOficiosData', [OficioController::class, 'tabla'])->name('oficios.tabla');
    Route::get('/dashboard-oficios/export-pdf', [OficioController::class, 'exportarAllOficiosPDF'])->name('oficios.tablaPDF');


    // Rangos
    Route::get('/dashboard-rangos', [RangoController::class, 'create'])->name('rangos.create');  
    Route::post('/dashboard-storeRangos', [RangoController::class, 'store'])->name('rangos.store');  
    Route::put('/dashboard-updateRangos', [RangoController::class, 'update'])->name('rangos.update');  
    Route::delete('/dashboard-disableRangos', [RangoController::class, 'disable'])->name('rangos.disable');  
    Route::post('/dashboard-restoreRangos', [RangoController::class, 'restore'])->name('rangos.restore');  
    Route::delete('/dashboard-deleteRangos', [RangoController::class, 'delete'])->name('rangos.delete');  
    Route::get('/dashboard-rangos/export-pdf', [RangoController::class, 'exportarAllRangosPDF'])->name('rangos.tablaPDF');

    
    // Configuración
    Route::get('/dashboard-configuracion', [ConfiguracionController::class, 'create'])->name('configuracion.create');  
    Route::put('/dashboard-updateConfiguracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');


    // Notificaciones
    Route::post('/systemNotification/deactivateNotification', [SystemNotificationController::class, 'deactivateNotification'])->name('systemNotification.deactivate');


    // Correos
    Route::get('emailExample', function () {
        Mail::to('garciabetancourtjosue@gmail.com')
            ->send(new ResetPasswordMail);
        return "Mensaje enviado";
    })->name('emailExample');
});

require __DIR__.'/auth.php';

