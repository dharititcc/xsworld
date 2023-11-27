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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('parent_item_id')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0=Pending, 1=Accepted, 2=Ready, 3=Completed, 4=Canceled, 11=ReadyForPickup, 12=kitchen_confirm,15=waiter_pending')->after('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('categories_category_id_foreign');
            $table->dropColumn('category_id');
            $table->dropColumn('status');

        });
    }
};
