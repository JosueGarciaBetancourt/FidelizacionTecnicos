<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Canjes', function (Blueprint $table) {
            $table->string('idCanje', 10); //CANJ-00001
            $table->string('idRecompensa', 9);
            $table->string('idVentaIntermediada', 13);
            
            // Estableciendo la clave primaria compuesta solo por idCanje e idRecompensa
            $table->primary(['idCanje', 'idRecompensa']);
            
            $table->foreign('idVentaIntermediada')
                    ->references('idVentaIntermediada')->on('VentasIntermediadas')
                    ->onDelete('cascade');
            $table->foreign('idRecompensa')
                    ->references('idRecompensa')->on('Recompensas')
                    ->onDelete('cascade');
            
            $table->dateTime('fechaHora_Canje')->default(now());
            $table->integer('cantidadRecompensa_Canje')->default(1);
            $table->integer('totalPuntosCanjeados_Canje')->unsigned(); //50
            $table->integer('diasTranscurridos_Canje')->unsigned(); //30
            $table->string('rutaPDF_Canje')->nullable(); //public/detallesCanje/CANJ-00001.pdf
            $table->unsignedBigInteger('idUser');
           
            $table->foreign('idUser')
                    ->references('id')->on('users')
                    ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Canjes');
    }
};
