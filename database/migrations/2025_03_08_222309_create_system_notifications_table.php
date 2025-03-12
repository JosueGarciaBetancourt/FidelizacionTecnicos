<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('icon'); // request_page, timer, workspace_premium
            $table->string('title'); // Nueva solicitud de canje
            $table->string('tblToFilter'); // tblTecnicos
            $table->string('item'); // SOLICANJ-00003, F001-00000072, 77043114|Josué Daniel García
            $table->text('description'); // recibida desde app móvil
            $table->text('routeToReview')->nullable(); // tecnicos.create
            $table->boolean('active')->default(true); // Para poder desactivar notificaciones revisadas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
