<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Oficios', function (Blueprint $table) {
            $table->id("idOficio");
            $table->string("nombre_Oficio");
            $table->timestamps();
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Oficios');
    }
};
