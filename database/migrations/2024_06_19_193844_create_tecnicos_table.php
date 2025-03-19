<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Tecnicos', function (Blueprint $table) {
            $table->string('idTecnico', 8)->primary(); 
            $table->string('nombreTecnico', 100); 
            $table->string('celularTecnico', 9); 
            $table->date('fechaNacimiento_Tecnico');  
            $table->integer('totalPuntosActuales_Tecnico')->unsigned()->default(0);
            $table->integer('historicoPuntos_Tecnico')->unsigned()->default(0);
            
            $table->unsignedBigInteger('idRango')->default(1);
            $table->foreign('idRango')->references('idRango')->on('Rangos')->onDelete('cascade');

            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Tecnicos');
    }
};
