<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantCategorySeeder extends Seeder
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
        $restaurants = Restaurant::all();

        // create categories of the restaurant
        $categories = [
            [
                'name'      => 'Food',
                'status'    => 1
            ],
            [
                'name'      => 'Drinks',
                'status'    => 1
            ]
        ];

        if( $restaurants->count() )
        {
            foreach( $restaurants as $restaurant )
            {
                if( !empty( $categories ) )
                {
                    foreach( $categories as $category )
                    {
                        $restaurant->categories()->create($category);
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
