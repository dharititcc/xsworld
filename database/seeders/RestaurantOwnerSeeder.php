<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantOwnerSeeder extends Seeder
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
                'id'                    => 2,
                'first_name'            => 'Milan',
                'last_name'             => 'Soni',
                'email'                 => 'milan@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265925',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 3,
                'first_name'            => 'Athar',
                'last_name'             => 'Marfatiya',
                'email'                 => 'athar@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265926',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 4,
                'first_name'            => 'Dharit',
                'last_name'             => 'Maniyar',
                'email'                 => 'dharit@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265927',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 5,
                'first_name'            => 'Dhaval',
                'last_name'             => 'Panchal',
                'email'                 => 'dhaval@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265928',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 6,
                'first_name'            => 'Maulik',
                'last_name'             => 'Panchal',
                'email'                 => 'maulik@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265929',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 7,
                'first_name'            => 'Hardik',
                'last_name'             => 'Kanzariya',
                'email'                 => 'hardik@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265930',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 8,
                'first_name'            => 'Chirag',
                'last_name'             => 'Shah',
                'email'                 => 'chirag@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265931',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 9,
                'first_name'            => 'Rahul',
                'last_name'             => 'Desai',
                'email'                 => 'rahul@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265932',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 10,
                'first_name'            => 'Xerxes',
                'last_name'             => 'Surty',
                'email'                 => 'xerxes@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265933',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 11,
                'first_name'            => 'Pearl',
                'last_name'             => 'Harbour',
                'email'                 => 'pearl@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265940',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 12,
                'first_name'            => 'Tabarak',
                'last_name'             => 'Salman',
                'email'                 => 'tabs@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265934',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 13,
                'first_name'            => 'Samarpan',
                'last_name'             => '',
                'email'                 => 'samarpan@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265935',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 14,
                'first_name'            => 'Bhupendra',
                'last_name'             => 'Panchal',
                'email'                 => 'bhupendra@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265936',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 15,
                'first_name'            => 'Bhavesh',
                'last_name'             => '',
                'email'                 => 'bhavesh@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265937',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 16,
                'first_name'            => 'Manas',
                'last_name'             => '',
                'email'                 => 'manas@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265938',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 17,
                'first_name'            => 'Kushang',
                'last_name'             => 'Dwivedi',
                'email'                 => 'kushang@itccdigital.com',
                'password'              => Hash::make('12345678'),
                'phone'                 => '7600265939',
                'user_type'             => User::RESTAURANT_OWNER,
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString(),
                'email_verified_at'     => Carbon::now()->toDateTimeString()
            ]
        ];

        if( !empty( $userArr ) )
        {
            foreach( $userArr as $singleUser )
            {
                User::create($singleUser);
            }
        }
    }
}
