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

        $drinksCategories = [
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

        $foodCategories = [
            [
                'name'  => 'Entree',
                'status'=> 1
            ],
            [
                'name'  => 'Mains',
                'status'=> 1
            ],
            [
                'name'  => "Chefs Special",
                'status'=> 1
            ],
            [
                'name'  => 'Sides',
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
                            if( !empty( $drinksCategories ) )
                            {
                                foreach( $drinksCategories as $subcategory )
                                {
                                    $subcategory['parent_id']   = $category->id;
                                    $newCategory                = $restaurant->categories()->create($subcategory);

                                    // attachment
                                    $newCategory->attachment()->create([
                                        'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.png',
                                        'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.png',
                                    ]);
                                }
                            }
                        }

                        if( $category->name === 'Food' )
                        {
                            if( !empty( $foodCategories ) )
                            {
                                foreach( $foodCategories as $foodSubCategory )
                                {
                                    $foodSubCategory['parent_id']   = $category->id;
                                    $newCategory                    = $restaurant->categories()->create($foodSubCategory);

                                    // attachment
                                    $newCategory->attachment()->create([
                                        'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.png',
                                        'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.png',
                                    ]);
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
