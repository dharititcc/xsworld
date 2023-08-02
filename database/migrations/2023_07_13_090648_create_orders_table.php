<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Actual customer who order');
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('restaurant_pickup_point_id');
            $table->unsignedBigInteger('pickup_point_user_id')->comment('Bar user who take order');
            $table->unsignedTinyInteger('type')->default(1)->comment('1=CART, 2=ORDER');
            $table->unsignedTinyInteger('status')->default(0)->comment('0=Pending, 1=Accepted, 2=Ready, 3=Completed, 4=Canceled');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('credit_point', 14,2)->default(0)->comment('Used from credit point');
            $table->decimal('amount', 14,2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->unsignedTinyInteger('apply_time')->nullable();
            $table->timestamp('accepted_date')->nullable();
            $table->decimal('total', 14,2)->default(0);
            $table->unsignedBigInteger('currency_id');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('restaurant_pickup_point_id')->references('id')->on('restaurant_pickup_points');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pickup_point_user_id')->references('id')->on('users');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
