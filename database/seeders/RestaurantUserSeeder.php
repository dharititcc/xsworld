<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userArr = [
            [
                'id'                    => 4,
                'name'                  => 'Sunny Gadani',
                'email'                 => 'dhaval@yopmail.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '918000686995',
                'user_type'             => User::BARTENDER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 5,
                'name'                  => 'Dhaval Panchal',
                'email'                 => 'dhaval@xsworld.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '918000686911',
                'user_type'             => User::BARTENDER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ]
        ];

        if( !empty( $userArr ) )
        {
            foreach( $userArr as $user )
            {
                User::create($user);
            }
        }
    }
}
