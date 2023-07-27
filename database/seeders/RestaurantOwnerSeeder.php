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
        User::create([
            'id'                    => 2,
            'name'                  => 'Milan Soni',
            'email'                 => 'milan@itccdigital.com',
            'password'              => Hash::make('12345678'),
            'phone'                 => '917600265925',
            'user_type'             => 2,
            'created_at' 	        => Carbon::now()->toDateTimeString(),
            'updated_at' 	        => Carbon::now()->toDateTimeString(),
            'email_verified_at'     => Carbon::now()->toDateTimeString()
        ]);
    }
}
