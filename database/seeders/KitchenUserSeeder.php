<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KitchenUserSeeder extends Seeder
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
                'first_name'            => 'Bran',
                'last_name'             => 'Doe',
                'email'                 => 'bran@yopmail.com',
                'username'              => 'K101',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567898',
                'user_type'             => User::KITCHEN,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'first_name'            => 'Christopher',
                'last_name'             => 'Nolan',
                'email'                 => 'nolan@yopmail.com',
                'username'              => 'K102',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567811',
                'user_type'             => User::KITCHEN,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'first_name'            => 'Adam',
                'last_name'             => 'Anderson',
                'email'                 => 'adam@yopmail.com',
                'username'              => 'K103',
                'password'              => Hash::make('12345678'),
                'phone'                 => '1234567832',
                'user_type'             => User::KITCHEN,
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
