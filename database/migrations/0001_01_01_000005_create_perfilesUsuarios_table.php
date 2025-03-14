<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PerfilesUsuarios', function (Blueprint $table) {
            $table->id("idPerfilUsuario");
            $table->string('nombre_PerfilUsuario', 50);
            /* $table->timestamps(); //created_at updated_at */
            // Definir created_at con CURRENT_TIMESTAMP por defecto
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); 
            /* $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP + INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable(); */
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PerfilesUsuarios');
    }
};
