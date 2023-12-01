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
        Schema::table('user_referrals', function (Blueprint $table) {
            $table->unsignedBigInteger('status')->default(0)->after('points_earned_by_from_user')->comment('0=>Sent(Pending),1=> Accepted,');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_referrals', function (Blueprint $table) {
            //
        });
    }
};
