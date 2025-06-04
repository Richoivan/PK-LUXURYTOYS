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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_type_id')->constrained();
            $table->foreignId('manufacturer_id')->constrained();
            $table->string('name');
            $table->year('year');
            $table->decimal('price', 20, 2);
            $table->string('color');
            $table->integer('mileage')->nullable();
            $table->string('transmission');
            $table->string('fuel_type');
            $table->string('engine_size')->nullable();
            $table->text('description');
            $table->string('status')->default('available'); 
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
