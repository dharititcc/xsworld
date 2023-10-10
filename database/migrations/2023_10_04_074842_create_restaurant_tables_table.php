<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('user_id')->nullable()->index()->comment('waiter id');
            $table->longText('qr_image')->nullable();
            $table->text('qr_url')->nullable();
            $table->integer('status')->comment("1 => Active , 0 => disable")->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('user_id')->references('id')->on('users');
        });


        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('waiter_id')->nullable()->comment('waiter/user id')->after('pickup_point_user_id');
            $table->unsignedBigInteger('restaurant_table_id')->nullable()->comment('restaurant_table_id')->after('waiter_id');
            $table->foreign('waiter_id')->references('id')->on('users');
            $table->foreign('restaurant_table_id')->references('id')->on('restaurant_tables');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_tables');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('users_waiter_id_foreign');
            $table->dropForeign('restaurant_tables_restaurant_table_id_foreign');
            $table->dropColumn('waiter_id');
            $table->dropColumn('restaurant_table_id');
        });
    }
}
