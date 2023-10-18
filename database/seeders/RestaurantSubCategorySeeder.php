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
                'name'  => 'Wines',
                'status'=> 1
            ],
            [
                'name'  => 'Champagne',
                'status'=> 1
            ],
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

                if( $restaurant->id == 1 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 2 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 3 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 4 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 5 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 6 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 7 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 8 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 9 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 10 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 11 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 12 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 13 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 14 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 15 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 16 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 17 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 18 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 19 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 20 )
                {
                    if( !empty( $categories ) )
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($subcategory['name'])).'.jpg',
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
                                            'stored_name'   => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                            'original_name' => str_replace(' ', '_', strtolower($foodSubCategory['name'])).'.jpg',
                                        ]);
                                    }
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
