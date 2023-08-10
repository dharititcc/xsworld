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
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('phone2')->nullable();
            $table->unsignedTinyInteger('registration_type')->default(0)->comment('0=EMAIL, 1=PHONE, 2=GOOGLE, 3=FACEBOOK');
            $table->unsignedTinyInteger('user_type')->default(1)->comment('1=CUSTOMER, 2=RESTAURANT_OWNER, 3=ADMIN, 4=BARTENDER');
            $table->string('country')->nullable();
            $table->string('country_code',5)->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('credit_points', 14,2)->default(0);
            $table->string('platform')->nullable();
            $table->string('os_version')->nullable();
            $table->string('application_version')->nullable();
            $table->string('model')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('fcm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
