<?php

use Illuminate\Support\Facades\DB;
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
            //$table->timestamps(); //created_at updated_at
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();	
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Oficios');
    }
};
