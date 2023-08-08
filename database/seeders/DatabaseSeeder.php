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
            VariationSeeder::class,
            ItemSeeder::class,
            PickupPointSeeder::class,
            CurrencySeeder::class,
            RestaurantSeeder::class,
            RestaurantUserSeeder::class,
            CountrySeeder::class
        ]);
    }
}
