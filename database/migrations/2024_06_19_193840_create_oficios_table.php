<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Oficios', function (Blueprint $table) {
            $table->id('idOficio'); // Autoincremental
            $table->string('nombre_Oficio');
            $table->string('descripcion_Oficio')->default('Sin descripción'); 
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Oficios');
    }
};
