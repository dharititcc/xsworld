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
        User::create([
            'id'                    => 3,
            'name'                  => 'Milan Soni',
            'email'                 => 'milan@yopmail.com',
            'password'              => Hash::make('12345678'),
            'phone1'                => '918000640987',
            'user_type'             => 1,
            'created_at' 	        => Carbon::now()->toDateTimeString(),
            'updated_at' 	        => Carbon::now()->toDateTimeString(),
            'email_verified_at'     => Carbon::now()->toDateTimeString()
        ]);
    }
}
