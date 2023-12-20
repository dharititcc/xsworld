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
        Schema::create('user_git_card', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('from_user');
            $table->string('to_user')->nullable();
            $table->integer('amount');
            $table->string('code');
            $table->unsignedBigInteger('verify_user_id')->nullable();
            $table->text('transaction_id')->nullable();
            $table->tinyInteger('status')->comment("1 => Redeemed , 0 => Pending")->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verify_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_git_card');
    }
};
