<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('SolicitudesCanjesRecompensas', function (Blueprint $table) {
            $table->string('idSolicitudCanje', 14); // ID de solicitud de canje (SOLICANJ-00001)
            $table->string('idRecompensa', 9); // ID de recompensa (RECOM-001)
            $table->integer('cantidad')->unsigned(); 
            $table->double('costoRecompensa')->unsigned(); // Costo de la recompensa en puntos

            // Relaciones
            $table->foreign('idSolicitudCanje')->references('idSolicitudCanje')->on('SolicitudesCanjes')->onDelete('cascade');
            $table->foreign('idRecompensa')->references('idRecompensa')->on('Recompensas')->onDelete('cascade');

            $table->timestamps(); //created_at updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('SolicitudesCanjesRecompensas');
    }
};
