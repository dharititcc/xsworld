<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantItemVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_item_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->unsignedBigInteger('restaurant_item_id');
            $table->decimal('price', 14,2)->default(0);
            $table->unsignedInteger('quantity')->default(0)->comment('Available Stock');

            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->foreign('restaurant_item_id')->references('id')->on('restaurant_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_item_variations');
    }
}
