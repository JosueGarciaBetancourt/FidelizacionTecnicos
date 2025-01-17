<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PerfilesUsuarios', function (Blueprint $table) {
            $table->id("idPerfilUsuario");
            $table->string('nombre_PerfilUsuario', 50);
            $table->timestamps();
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PerfilesUsuarios');
    }
};
