<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('VentasIntermediadas', function (Blueprint $table) {
            $table->string('idVentaIntermediada', 13)->primary(); // F001-00000072 xml

            $table->string('idTecnico', 8); // 77043114
            $table->foreign('idTecnico')->references('idTecnico')->on('Tecnicos');
                
            $table->string('nombreTecnico', 255);   
            $table->string('tipoCodigoCliente_VentaIntermediada', 3); // DNI - RUC xml
            $table->string('codigoCliente_VentaIntermediada', 11); // 77043114 - 10703047951 xml
            $table->string('nombreCliente_VentaIntermediada', 100); // Josué García Betancourt xml
            $table->dateTime('fechaHoraEmision_VentaIntermediada'); // xml
            $table->dateTime('fechaHoraCargada_VentaIntermediada')->useCurrent();
            $table->decimal('montoTotal_VentaIntermediada', 10, 2)->unsigned(); //200.50 xml
            $table->integer('puntosGanados_VentaIntermediada')->unsigned(); //201 (redondear el monto total del xml)
            $table->integer('puntosActuales_VentaIntermediada')->unsigned(); 
            
            $table->unsignedBigInteger('idEstadoVenta')->default(1); //1: En espera, 2: Redimido (parcial), 3: Redimido (completo), 4: Tiempo Agotado
            $table->foreign('idEstadoVenta')->references('idEstadoVenta')->on('EstadoVentas');
            
            $table->boolean('apareceEnSolicitud')->default(0);
            
            $table->timestamps(); //created_at updated_at

            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('VentasIntermediadas');
    }
};
