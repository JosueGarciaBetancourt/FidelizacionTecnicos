<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_tecnicos', function (Blueprint $table) {
            $table->string('idTecnico', 8)->primary();  // Cambiado a 'idTecnico' para consistencia
            $table->string('password');
            $table->foreign('idTecnico')  // Relacionando correctamente 'idTecnico' con la tabla 'Tecnicos'
                  ->references('idTecnico')
                  ->on('Tecnicos')
                  ->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_tecnicos');
    }
};