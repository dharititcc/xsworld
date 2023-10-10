<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantItems extends Migration
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
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('For Addons/Mixers');
            $table->unsignedTinyInteger('type')->comment('1=Addon, 2=Item, 3=Mixers');
            $table->unsignedTinyInteger('is_variable')->default(0)->comment('0=Simple Product, 1=Variable Product');
            $table->decimal('price', 14,2)->default(0);
            $table->string('ingredients')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('year_of_production')->nullable();
            $table->string('type_of_drink')->nullable();
            $table->tinyInteger('is_featured')->default(0)->comment('0=Not Featured, 1=Featured');
            $table->tinyInteger('is_available')->default(0)->comment('1=available,0=Not available');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('restaurant_items')->onDelete('cascade');
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
        Schema::dropIfExists('restaurant_items');
    }
}
