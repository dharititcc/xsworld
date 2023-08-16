<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // get restaurants
        $restaurants = Restaurant::with(['categories'])->get();

        $restaurantitemArr = [
            [
                'name'          => 'Less Ice',
                'type'          => RestaurantItem::ADDON,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Extra Ice',
                'type'          => RestaurantItem::ADDON,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Lemon',
                'type'          => RestaurantItem::ADDON,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Lime',
                'type'          => RestaurantItem::ADDON,
                'is_variable'   => 1,
                'price'         => 10
            ],
        ];

        if( $restaurants->count() )
        {
            foreach( $restaurants as $restaurant )
            {

                // get all the categories
                $categories = $restaurant->categories;
                if( $categories->count() )
                {
                    foreach( $categories as $category )
                    {
                        if( $category->name == 'Drinks' )
                        {
                            if( !empty( $restaurantitemArr ) )
                            {
                                foreach( $restaurantitemArr as $item )
                                {
                                    $item['restaurant_id'] = $restaurant->id;
                                    $item['category_id'] = $category->id;
                                    $newItem = RestaurantItem::create($item);
                                }
                            }
                        }
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
