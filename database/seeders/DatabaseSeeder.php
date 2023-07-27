<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            RestaurantOwnerSeeder::class,
            CustomerSeeder::class,
            ItemTypeSeeder::class,
            ItemSeeder::class,
            PickupPointSeeder::class,
            RestaurantSeeder::class,
            RestaurantUserSeeder::class
        ]);
    }
}
