<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->timestamps(); //created_at updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TecnicosOficios');
    }
};
