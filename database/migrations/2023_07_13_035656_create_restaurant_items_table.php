<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('price', 14,2);
            $table->unsignedTinyInteger('is_featured')->comment('0=Not Featured, 1=Featured');
            $table->string('variation')->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('0=Simple, 1=Variable');
            $table->unsignedBigInteger('restaurant_item_id')->nullable()->comment('Addon/Mixers of specific item / specific restaurant');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('restaurant_item_id')->references('id')->on('restaurant_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_items');
    }
}
