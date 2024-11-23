<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('EstadosSolicitudesCanjes', function (Blueprint $table) {
            $table->id('idEstadoSolicitudCanje'); // ID único para cada estado
            $table->string('nombre_EstadoSolicitudCanje', 20); // Nombre del estado, e.g., 1 'pendiente', 2 'aprobado', 3 'rechazado'
            $table->timestamps(); //created_at updated_at
            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('EstadosSolicitudesCanjes');
    }
};
