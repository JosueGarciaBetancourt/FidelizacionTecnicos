<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TecnicosOficios', function (Blueprint $table) {
            $table->string('idTecnico', 8);
            $table->unsignedBigInteger('idOficio');

            // Definir las llaves foráneas
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos')->onDelete('cascade');
            $table->foreign('idOficio')->references('idOficio')->on('Oficios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TecnicosOficios');
    }
};
