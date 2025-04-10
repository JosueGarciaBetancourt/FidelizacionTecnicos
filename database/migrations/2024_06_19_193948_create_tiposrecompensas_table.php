<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TiposRecompensas', function (Blueprint $table) {
            $table->id('idTipoRecompensa');
            $table->string('nombre_TipoRecompensa', 50)->unique()->comment('Nombre del tipo de recompensa, único para cada tipo'); // Nombre único
            $table->string('descripcion_TipoRecompensa')->default('Sin descripción');
            $table->string('colorTexto_TipoRecompensa')->default('#3206B0');
            $table->string('colorFondo_TipoRecompensa')->default('#DCD5F0');
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TiposRecompensas');
    }
};
