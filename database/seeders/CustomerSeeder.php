<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newUser = User::create([
            'id'                    => 18,
            'first_name'            => 'Milan',
            'last_name'             => 'Soni',
            'email'                 => 'milan@yopmail.com',
            'password'              => Hash::make('12345678'),
            'phone'                 => '8000640987',
            'user_type'             => User::CUSTOMER,
            'stripe_customer_id'    => 'cus_Od1ioRmWDYFHcR',
            'created_at' 	        => Carbon::now()->toDateTimeString(),
            'updated_at' 	        => Carbon::now()->toDateTimeString(),
            'email_verified_at'     => Carbon::now()->toDateTimeString()
        ]);

        // add user payment methods
        $newUser->payment_methods()->create([
            'name'  => 'Cash'
        ]);

        // add user payment methods
        $newUser->payment_methods()->create([
            'name'  => 'Credit Card'
        ]);
    }
}
