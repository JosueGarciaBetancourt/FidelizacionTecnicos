<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Canjes', function (Blueprint $table) {
            $table->string('idCanje', 10)->primary(); //CANJ-00001 (se genera automáticamente)
            $table->string('idVentaIntermediada', 13);
            $table->dateTime('fechaHoraEmision_VentaIntermediada');
            $table->dateTime('fechaHora_Canje')->useCurrent();
            $table->integer('diasTranscurridos_Canje')->unsigned(); 
            $table->integer('puntosComprobante_Canje')->unsigned();
            $table->integer('puntosCanjeados_Canje')->unsigned();
            $table->integer('puntosRestantes_Canje')->unsigned(); 
            $table->text('comentario')->nullable();
            $table->unsignedBigInteger('idUser');

            $table->foreign('idVentaIntermediada')->references('idVentaIntermediada')->on('VentasIntermediadas');
            $table->foreign('idUser')->references('id')->on('users');

            $table->json('recompensas_Canje')->nullable(); /*
                                                        {"idRecompensa": "RECOM-001", "cantidad": 2},
                                                        {"idRecompensa": "RECOM-002", "cantidad": 5},
                                                        {"idRecompensa": "RECOM-003", "cantidad": 1}
                                                     */
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Canjes');
    }
};
