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
        Schema::create('rental_equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->index('asset_id');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('rental_products')->onDelete('cascade');
            $table->index('product_id');

            $table->text('description')->nullable();
            $table->string('widget_image')->nullable();
            $table->boolean('widget_display')->default(false);
            $table->integer('min_amount')->nullable();
            $table->integer('max_amount')->nullable();
            $table->boolean('require_min')->default(false);
            $table->integer('tax_template')->nullable();
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
