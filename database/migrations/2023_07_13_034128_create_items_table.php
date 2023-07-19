<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
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
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->unsignedTinyInteger('type')->comment('1=Addon, 2=Item, 3=Mixers');
            $table->unsignedTinyInteger('is_variable')->default(0)->comment('0=Simple Product, 1=Variable Product');
            $table->decimal('price', 14,2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_type_id')->references('id')->on('item_types');
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
