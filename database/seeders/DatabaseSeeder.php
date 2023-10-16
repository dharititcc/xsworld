<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Restaurant;
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
            RestaurantBarTenderSeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            RestaurantSeeder::class,
            RestaurantCategorySeeder::class,
            RestaurantSubCategorySeeder::class,
            RestaurantPickupPointSeeder::class,
            RestaurantItemSeeder::class,
            AddonsSeeder::class,
            MixtureSeeder::class,
            EventSeeder::class,
            // OrderSeeder::class,
            DaySeeder::class,
            KitchenUserSeeder::class
        ]);
    }
}
