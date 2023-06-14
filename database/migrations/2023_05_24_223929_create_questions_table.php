<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('rental_products')->onDelete('cascade');
            $table->index('product_id');
            $table->string('question');
            $table->string('type')->nullable();
            $table->binary('widget_image')->nullable();
            $table->boolean('yes_no')->default(false);
            $table->integer('add_charge_id')->nullable();
            $table->integer('followup_question')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rental_equipment_types');
    }
};
