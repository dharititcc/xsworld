<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->double('latitude');
            $table->double('longitude');
            $table->text('address')->nullable();
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('specialisation')->nullable();
            $table->integer('status')->comment('0=Inactive, 1=Active');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
