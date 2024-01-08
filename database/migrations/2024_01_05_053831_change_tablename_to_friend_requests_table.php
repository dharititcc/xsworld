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
        Schema::rename('friend_requests', 'friendships');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('friendships', 'friend_requests');
    }
};
