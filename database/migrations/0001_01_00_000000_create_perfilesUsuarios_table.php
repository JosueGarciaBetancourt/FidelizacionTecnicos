<?php

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
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PerfilesUsuarios');
    }
};
