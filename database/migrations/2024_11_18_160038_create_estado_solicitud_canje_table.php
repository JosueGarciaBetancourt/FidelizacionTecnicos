<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('EstadosSolicitudesCanjes', function (Blueprint $table) {
            $table->id('idEstadoSolicitudCanje'); // ID único para cada estado
            $table->string('nombre_EstadoSolicitudCanje', 20); // Nombre del estado, e.g., 1 'pendiente', 2 'aprobado', 3 'rechazado'
            //$table->timestamps(); //created_at updated_at
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();	

            $table->softDeletes(); //deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('EstadosSolicitudesCanjes');
    }
};
