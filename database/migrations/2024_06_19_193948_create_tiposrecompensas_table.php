<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TiposRecompensas', function (Blueprint $table) {
            $table->id('idTipoRecompensa');
            $table->string('nombre_TipoRecompensa', 50)
                    ->unique()
                    ->comment('Nombre del tipo de recompensa, único para cada tipo'); // Nombre único
            $table->timestamps(); // created_at updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TiposRecompensas');
    }
};
