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
            $table->string('type'); // request_page, timer, workspace_premium
            $table->string('title');
            $table->text('description');
            $table->text('routeToReview')->nullable(); // Ruta para "Revisar"
            $table->boolean('active')->default(true); // Para poder desactivar notificaciones antiguas
            //$table->timestamps(); //created_at updated_at
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();	
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
