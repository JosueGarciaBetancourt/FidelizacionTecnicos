<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPerfilUsuario');
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique(); // Correo corporativo
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->string('DNI')->unique()->nullable();
            $table->string('surname')->nullable();
            $table->dateTime('fechaNacimiento')->nullable();
            $table->string('correoPersonal')->unique()->nullable();
            $table->string('celularPersonal')->unique()->nullable();
            $table->string('celularCorporativo')->nullable();

            $table->foreign('idPerfilUsuario')->references('idPerfilUsuario')->on('PerfilesUsuarios')->onDelete('no action');
            
            //$table->timestamps(); //created_at updated_at
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes(); // deleted_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            //$table->timestamps(); //created_at updated_at
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
