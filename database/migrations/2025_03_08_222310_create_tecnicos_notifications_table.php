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
            $table->string('idVentaIntermediada', 13)->nullable(); // Solo se llena este campo si es una notificación de agotamiento
                                                                    // de venta intermediada
            $table->string('idSolicitudCanje', 14)->nullable(); // Solo se llena este campo si es una notificación de aprobación o rechazo
                                                                // de solicitud de canje
            $table->text('description'); 
            $table->boolean('active')->default(true); // Para poder desactivar notificaciones revisadas

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos_notifications');
    }
};
