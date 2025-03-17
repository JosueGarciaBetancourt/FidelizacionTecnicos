<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); 
            $table->string('key')->unique(); // emailDomain, adminUsername
            $table->text('value'); // @dimacoftest.com, admintest
            /*$table->timestamps();*/
            $table->timestamp('created_at')->default(DB::raw('(CURRENT_TIMESTAMP - INTERVAL 5 HOUR)'));
            $table->timestamp('updated_at')->nullable();	
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
