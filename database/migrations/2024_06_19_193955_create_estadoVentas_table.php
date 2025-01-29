<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('EstadoVentas', function (Blueprint $table) {
            $table->id('idEstadoVenta'); //1, 2, 3, 4, ...
            $table->string('nombre_EstadoVenta', 100); //En espera, Redimido (parcial), Redimido (completo), Tiempo Agotado
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('EstadoVentas');
    }
};
