<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('phone2')->nullable();
            $table->unsignedTinyInteger('registration_type')->default(0)->comment('0=EMAIL, 1=PHONE, 2=GOOGLE, 3=FACEBOOK');
            $table->unsignedTinyInteger('user_type')->default(1)->comment('1=CUSTOMER, 2=RESTAURANT, 3=ADMIN, 4=BARTENDER');
            $table->date('birth_date')->nullable();
            $table->decimal('credit_points', 14,2)->default(0);
            $table->string('platform')->nullable();
            $table->string('os_version')->nullable();
            $table->string('application_version')->nullable();
            $table->string('model')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
