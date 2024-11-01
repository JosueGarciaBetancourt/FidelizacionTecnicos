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
            $table->string('tipoRecompensa', 30)->nullable(); 
            $table->string('descripcionRecompensa', 100)->default('Sin descripción'); 
            $table->unsignedInteger('costoPuntos_Recompensa')->default(1); 
            $table->unsignedInteger('stock_Recompensa')->default(1); 
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Recompensas');
    }
};
