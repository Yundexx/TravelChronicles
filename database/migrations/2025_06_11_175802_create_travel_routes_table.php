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
        Schema::create('travel_routes', function (Blueprint $table) {
            $table->id();
            $table->timestamps(); //created_at and updated_at
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('start_location');
            $table->string('end_location');
            $table->string('country_name');
            $table->string('city_name')->nullable()->default('-'); // Default to '-' if not provided
            $table->boolean('flagged')->default(false); // Flag for inspection 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_routes');
    }
};
