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
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('parent_item_id')->nullable();
            $table->unsignedBigInteger('quantity');
            $table->decimal('price', 14,2)->default(0);
            $table->unsignedTinyInteger('type')->default(1)->comment('0=Addon, 1=Item, 2=Mixers');
            $table->decimal('total', 14,2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('parent_item_id')->references('id')->on('items');
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
