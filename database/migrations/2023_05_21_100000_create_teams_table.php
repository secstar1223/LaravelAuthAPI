<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->timestamp('date_join')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_route')->nullable();
            $table->integer('front_percent')->nullable();
            $table->integer('back_percent')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->enum('country', \Config::get('constants.countries'))->default('United Kingdom');
            $table->enum('timezone', \Config::get('constants.timezones'))->default('Europe/London');
            $table->string('website')->nullable();
            $table->enum('currency', \Config::get('constants.currencies'))->default('EUR');
            $table->string('cc_disputes_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
