<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CanjesRecompensas', function (Blueprint $table) {
            $table->string('idCanje', 10); //CANJ-00001 (se genera automáticamente)
            $table->string('idRecompensa', 13);
            $table->unsignedBigInteger('idUser');
            $table->integer('cantidad')->unsigned(); 
            $table->double('costoRecompensa')->unsigned();
            $table->text('comentario')->unsigned();

            // Definir las llaves foráneas
            $table->foreign('idCanje')->references('idCanje')->on('Canjes')->onDelete('cascade');
            $table->foreign('idRecompensa')->references('idRecompensa')->on('Recompensas')->onDelete('cascade');
            $table->foreign('idUser')->references('id')->on('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CanjesRecompensas');
    }
};
