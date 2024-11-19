<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Recompensas', function (Blueprint $table) {
            $table->string('idRecompensa', 9)->primary(); //RECOM-001
            $table->unsignedBigInteger('idTipoRecompensa');
            $table->string('descripcionRecompensa')->default('Sin descripción'); 
            $table->unsignedInteger('costoPuntos_Recompensa')->default(1); 
            $table->unsignedInteger('stock_Recompensa')->nullable(); 
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
    
            $table->foreign('idTipoRecompensa')->references('idTipoRecompensa')->on('TiposRecompensas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Recompensas');
    }
};
