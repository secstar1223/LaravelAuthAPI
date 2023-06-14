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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('rental_products')->onDelete('cascade');
            $table->index('product_id');
            $table->enum('times', \Config::get('constants.times'));
            $table->integer('start_time');
            $table->integer('end_time');
            $table->integer('starts_every');
            $table->boolean('mon');
            $table->boolean('tue');
            $table->boolean('wed');
            $table->boolean('thu');
            $table->boolean('fri');
            $table->boolean('sat');
            $table->boolean('sun');
            $table->text('starts_specific');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
