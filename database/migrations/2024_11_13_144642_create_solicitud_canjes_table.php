<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear tabla de Estados de Canje
        Schema::create('EstadosCanje', function (Blueprint $table) {
            $table->id('idEstadoCanje'); // ID único para cada estado
            $table->string('descripcion', 50); // Descripción del estado, e.g., 'pendiente', 'aprobado', 'rechazado'
            $table->timestamps();
        });

        // Crear tabla de Solicitudes de Canje
        Schema::create('SolicitudesCanje', function (Blueprint $table) {
            $table->string('idSolicitudCanje', 13)->primary(); // Formato SOLICANJ-00001
            $table->string('idVentaIntermediada', 13); // ID del comprobante de venta
            $table->string('idTecnico', 8); // ID del técnico que hace la solicitud
            $table->unsignedBigInteger('idEstadoCanje')->default(1); // Estado de la solicitud con referencia a EstadosCanje
            $table->timestamp('fechaSolicitud')->useCurrent(); // Fecha de creación de la solicitud

            // Relaciones
            $table->foreign('idVentaIntermediada')->references('idVentaIntermediada')->on('VentasIntermediadas');
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos');
            $table->foreign('idEstadoCanje')->references('idEstadoCanje')->on('EstadosCanje');

            $table->timestamps();
        });

        // Crear tabla intermedia entre Solicitudes de Canje y Recompensas
        Schema::create('SolicitudCanjeRecompensas', function (Blueprint $table) {
            $table->string('idCanje', 13); // ID de solicitud de canje (SOLICANJ-00001)
            $table->string('idRecompensa', 9); // ID de recompensa (RECOM-001)
            $table->integer('cantidad')->unsigned(); 
            $table->double('costoRecompensa')->unsigned(); // Costo de la recompensa en puntos

            // Relaciones
            $table->foreign('idCanje')->references('idSolicitudCanje')->on('SolicitudesCanje')->onDelete('cascade');
            $table->foreign('idRecompensa')->references('idRecompensa')->on('Recompensas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EstadosCanje');
        Schema::dropIfExists('SolicitudesCanje');
        Schema::dropIfExists('SolicitudCanjeRecompensas');
    }
};
