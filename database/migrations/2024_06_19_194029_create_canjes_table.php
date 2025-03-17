<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Canjes', function (Blueprint $table) {
            $table->string('idCanje', 10)->primary(); // CANJ-00001 (se genera automáticamente)
            $table->string('idVentaIntermediada', 13);
            $table->dateTime('fechaHoraEmision_VentaIntermediada');
            $table->dateTime('fechaHora_Canje')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->integer('diasTranscurridos_Canje')->unsigned(); 
            $table->integer('puntosComprobante_Canje')->unsigned(); // Puntos generados
            $table->integer('puntosActuales_Canje')->unsigned()->nullable();
            $table->integer('puntosCanjeados_Canje')->unsigned();
            $table->integer('puntosRestantes_Canje')->unsigned(); 
            $table->text('comentario_Canje')->nullable();
            $table->unsignedBigInteger('idUser');

            $table->foreign('idVentaIntermediada')->references('idVentaIntermediada')->on('VentasIntermediadas');
            $table->foreign('idUser')->references('id')->on('users');

            $table->timestamps(); //created_at updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Canjes');
    }
};
