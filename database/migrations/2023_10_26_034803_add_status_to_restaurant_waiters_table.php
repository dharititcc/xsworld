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
        Schema::table('restaurant_waiters', function (Blueprint $table) {
            $table->integer('status')->comment("1 => Online , 0 => Offline")->default(1)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_waiters', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
