<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Tecnicos', function (Blueprint $table) {
            $table->string('idTecnico', 8)->primary(); 
            $table->string('nombreTecnico', 100); 
            $table->string('celularTecnico', 9); 
            $table->string('oficioTecnico', 50); 
            $table->date('fechaNacimiento_Tecnico');  
            $table->integer('totalPuntosActuales_Tecnico')->unsigned()->default(0);
            $table->integer('historicoPuntos_Tecnico')->unsigned()->default(0);
            $table->string('rangoTecnico')->default('Plata');
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Tecnicos');
    }
};
