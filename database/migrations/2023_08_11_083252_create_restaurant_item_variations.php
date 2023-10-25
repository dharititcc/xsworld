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
            $table->string('name');
            $table->decimal('price', 14,2)->default(0);
            $table->unsignedBigInteger('restaurant_item_id');
            $table->timestamps();
            $table->softDeletes();

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
