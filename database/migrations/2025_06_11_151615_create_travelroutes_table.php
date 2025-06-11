<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travelroutes', function (Blueprint $table) {
            $table->id();
            $table->timestamps(); //created_at, updated_at
            $table->string('travelroute_name');
            $table->string('userid');
            // $table->boolean('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelroutes');
    }
};
