<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = new User();
        $user = $adminUser->create([
            'id'                    => 1,
            'name'                  => 'Vrushank Shah',
            'email'                 => 'vrushank@appmart.com.au',
            'password'              => Hash::make('password'),
            'phone'                 => '61430147853',
            'user_type'             => User::ADMIN,
            'created_at' 	        => Carbon::now()->toDateTimeString(),
            'updated_at' 	        => Carbon::now()->toDateTimeString(),
            'email_verified_at'     => Carbon::now()->toDateTimeString()
        ]);
    }
}
