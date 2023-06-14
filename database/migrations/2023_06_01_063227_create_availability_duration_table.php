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
        Schema::create('availability_duration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('availability_id');
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('cascade');
            $table->index('availability_id');
            $table->unsignedBigInteger('duration_id');
            $table->foreign('duration_id')->references('id')->on('durations')->onDelete('cascade');
            $table->index('duration_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availablity_duration');
    }
};
