<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // request_page, timer, workspace_premium
            $table->string('title');
            $table->text('description');
            $table->text('routeToReview')->nullable(); // Ruta para "Revisar"
            $table->boolean('active')->default(true); // Para poder desactivar notificaciones antiguas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
