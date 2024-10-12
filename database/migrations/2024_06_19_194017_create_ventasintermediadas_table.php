<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('VentasIntermediadas', function (Blueprint $table) {
            $table->string('idVentaIntermediada', 13)->primary(); //F001-00000072 xml

            $table->string('idTecnico', 8);
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos');
            $table->string('nombreTecnico', 255);   
            
            $table->string('tipoCodigoCliente_VentaIntermediada', 3); //DNI - RUC xml
            $table->string('codigoCliente_VentaIntermediada', 11); //77043114 - 10703047951 xml
            $table->string('nombreCliente_VentaIntermediada', 100); //Josué García Betancourt xml
            $table->dateTime('fechaHoraEmision_VentaIntermediada'); //xml
            $table->dateTime('fechaHoraCargada_VentaIntermediada')->useCurrent();
            $table->double('montoTotal_VentaIntermediada')->unsigned(); //200.50 xml
            $table->integer('puntosGanados_VentaIntermediada')->unsigned(); //201 (redondear el monto total del xml)
            $table->string('estadoVentaIntermediada', 14)->default('En espera'); //Tiempo Agotado, Redimido, En espera
            $table->timestamps(); //created_at updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('VentasIntermediadas');
    }
};
