<?php

use App\Http\Controllers\CanjeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecompensaController;
use App\Http\Controllers\VentaIntermediadaController;
use App\Http\Controllers\TecnicoController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\VentaIntermediada;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/tblTecnicos', [TecnicoController::class, 'tabla'])->name('tecnicos.tabla');  


    Route::get('/dashboard-ventasIntermediadas', [VentaIntermediadaController::class, 'create'])->name('ventasIntermediadas.create');
    Route::post('/modal-storeVenta', [VentaIntermediadaController::class, 'store'])->name('ventasIntermediadas.store');
    
    Route::get('/dashboard-canjes', [CanjeController::class, 'create'])->name('canjes.create');  
    
    // Fetch
    route::get('/dashboard-canjes/tecnico/{idTecnico}', [VentaIntermediadaController::class, 'getComprobantesEnEsperaByIdTecnico'])
        ->name('getVentasIntermediadasWithTecnico');

    Route::get('/dashboard-recompensas', [RecompensaController::class, 'create'])->name('recompensas.create');  
    Route::post('/modal-storeRecompensa', [RecompensaController::class, 'store'])->name('recompensas.store');  
    Route::put('/modal-updateRecompensa', [RecompensaController::class, 'update'])->name('recompensas.update'); 
    Route::delete('/modal-deleteRecompensa', [RecompensaController::class, 'delete'])->name('recompensas.delete');



    Route::get('/dashboard-tecnicos', [TecnicoController::class, 'create'])->name('tecnicos.create');  
    Route::post('/modal-storeTecnico', [TecnicoController::class, 'store'])->name('tecnicos.store');  
    Route::put('/modal-updateTecnico', [TecnicoController::class, 'update'])->name('tecnicos.update'); 
    Route::delete('/modal-deleteTecnico', [TecnicoController::class, 'delete'])->name('tecnicos.delete');


    Route::get('/dashboard-configuracion', [DashboardController::class, 'configuracion'])->name('configuracion');  

    Route::get('emailExample', function () {
        Mail::to('garciabetancourtjosue@gmail.com')
            ->send(new ResetPasswordMail);
        return "Mensaje enviado";
    })->name('emailExample');
});

require __DIR__.'/auth.php';

