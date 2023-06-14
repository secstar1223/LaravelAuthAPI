<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalProductTable extends Migration
{
    public function up()
    {
        Schema::create('rental_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->integer('tax_template')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->index('team_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_products');
    }
}
;
