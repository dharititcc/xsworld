<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_table_id')->nullable()->comment('restaurant_table_id');
            $table->unsignedBigInteger('waiter_id')->nullable()->comment('waiter/user id');
            $table->unsignedBigInteger('user_id')->nullable()->index()->comment('User/Customer id');
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->foreign('waiter_id')->references('id')->on('users');
            $table->foreign('restaurant_table_id')->references('id')->on('restaurant_tables');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tables');
    }
};
