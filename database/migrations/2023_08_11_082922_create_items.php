<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedTinyInteger('type')->comment('1=Addon, 2=Item, 3=Mixers');
            $table->unsignedTinyInteger('is_variable')->default(0)->comment('0=Simple Product, 1=Variable Product');
            $table->decimal('price', 14,2)->default(0);
            $table->unsignedBigInteger('is_featured');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
