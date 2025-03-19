<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rangos', function (Blueprint $table) {
            $table->id('idRango'); // Autoincremental
            $table->string('nombre_Rango');
            $table->string('descripcion_Rango')->default('Sin descripción'); 
            $table->unsignedInteger('puntosMinimos_Rango'); 
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Rangos');
    }
};
