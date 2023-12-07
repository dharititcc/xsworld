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
        Schema::table('credit_points_histories', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('credit_point');
            $table->dropColumn('debit_points');
            $table->dropColumn('total');
            $table->dropColumn('order_id');
            $table->string('model_name')->after('order_id');
            $table->unsignedBigInteger('model_id')->after('model_name');
            $table->decimal('points',14,2)->after('model_id');
            $table->unsignedTinyInteger('type')->default(1)->comment('0-Debit,1=Credit')->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_points_histories', function (Blueprint $table) {
            $table->dropColumn('model_name');
            $table->dropColumn('model_id');
            $table->dropColumn('points');
            $table->dropColumn('type');
        });
    }
};
