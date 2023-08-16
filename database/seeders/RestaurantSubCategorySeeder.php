<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSubCategorySeeder extends Seeder
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

        $subcategories = [
            [
                'name'  => 'Spirits',
                'status'=> 1
            ],
            [
                'name'  => 'Cocktails',
                'status'=> 1
            ],
            [
                'name'  => 'Beers',
                'status'=> 1
            ],
            [
                'name'  => 'Wine',
                'status'=> 1
            ]
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
                        if( $category->name === 'Drinks' )
                        {
                            if( !empty( $subcategories ) )
                            {
                                foreach( $subcategories as $subcategory )
                                {
                                    $subcategory['parent_id'] = $category->id;
                                    $restaurant->categories()->create($subcategory);
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
