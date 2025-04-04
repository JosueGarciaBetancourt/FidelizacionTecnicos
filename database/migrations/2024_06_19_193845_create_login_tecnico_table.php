<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_tecnicos', function (Blueprint $table) {
            $table->string('idTecnico', 8)->primary();
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos')->onDelete('cascade');

            $table->string('password');
            $table->boolean('isFirstLogin')->default(0); // columna para verificar si es el primer login
            $table->string('api_key')->nullable();
            $table->rememberToken();
            
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_tecnicos');
    }
};