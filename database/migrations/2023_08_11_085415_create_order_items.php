<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('restaurant_item_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('parent_item_id')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 14,2)->default(0);
            $table->unsignedTinyInteger('type')->default(1)->comment('1=Addon, 2=Item, 3=Mixers');
            $table->decimal('total', 14,2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('restaurant_item_id')->references('id')->on('restaurant_items');
            $table->foreign('parent_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('variation_id')->references('id')->on('restaurant_item_variations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
