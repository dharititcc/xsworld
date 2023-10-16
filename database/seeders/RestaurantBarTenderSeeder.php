<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantBarTenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurantbartenderArr = [
            [
                'id'                    => 19,
                'first_name'            => 'John',
                'last_name'             => 'Doe',
                'email'                 => 'john@yopmail.com',
                'username'              => 'E101',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567890',
                'user_type'             => User::BARTENDER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 20,
                'first_name'            => 'Test',
                'last_name'             => 'test2',
                'email'                 => 'test@yopmail.com',
                'username'              => 'E102',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567891',
                'user_type'             => User::BARTENDER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 21,
                'first_name'            => 'Dharit',
                'last_name'             => 'Maniyar',
                'email'                 => 'dharit@yopmail.com',
                'username'              => 'E103',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567892',
                'user_type'             => User::BARTENDER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ]
        ];

        if( !empty( $restaurantbartenderArr ) )
        {
            foreach( $restaurantbartenderArr as $bartender )
            {
                User::create($bartender);
            }
        }
    }
}
