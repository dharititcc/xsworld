<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->unsignedBigInteger('lft')->nullable();
            $table->unsignedBigInteger('rgt')->nullable();
            $table->unsignedBigInteger('depth')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('item_types');
    }
}
