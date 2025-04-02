<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('SolicitudesCanjes', function (Blueprint $table) {
            $table->string('idSolicitudCanje', 14)->primary(); // Formato SOLICANJ-00001
            $table->string('idVentaIntermediada', 13); // ID del comprobante de venta
            $table->dateTime('fechaHoraEmision_VentaIntermediada')->nullable();
            $table->unsignedBigInteger('idEstadoSolicitudCanje')->default(1); // Estado de la solicitud con referencia a EstadosCanje
            $table->string('idTecnico', 8); // ID del técnico que hace la solicitud
            $table->unsignedBigInteger('idUser')->nullable(); // Solo se registra cuando el estado es 'Aprobado' o 'Rechazado'
            $table->timestamp('fechaHora_SolicitudCanje')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->integer('diasTranscurridos_SolicitudCanje')->unsigned(); 
            $table->integer('puntosComprobante_SolicitudCanje')->unsigned(); // Puntos generados
            $table->integer('puntosActuales_SolicitudCanje')->unsigned();
            $table->integer('puntosCanjeados_SolicitudCanje')->unsigned();
            $table->integer('puntosRestantes_SolicitudCanje')->unsigned(); 
            $table->text('comentario_SolicitudCanje')->nullable(); // Cuando un usuario aprueba o rechaza se realiza un comentario

            $table->foreign('idVentaIntermediada')->references('idVentaIntermediada')->on('VentasIntermediadas');
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos');
            $table->foreign('idEstadoSolicitudCanje')->references('idEstadoSolicitudCanje')->on('EstadosSolicitudesCanjes');
            $table->foreign('idUser')->references('id')->on('users');

            $table->timestamps(); //created_at updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('SolicitudesCanjes');
    }
};
