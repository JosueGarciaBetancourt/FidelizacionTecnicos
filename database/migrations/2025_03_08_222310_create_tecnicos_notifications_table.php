<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tecnicos_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('idTecnico', 8); 
            // Si solo es una notificación de cambio de rango entonces quedará vacío este campo
            $table->string('idVentaIntermediada', 13)->nullable(); 
            $table->text('description'); 
            $table->boolean('active')->default(true); // Para poder desactivar notificaciones revisadas

            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos')->onDelete('cascade');
            $table->foreign('idVentaIntermediada')->references('idVentaIntermediada')->on('VentasIntermediadas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos_notifications');
    }
};
