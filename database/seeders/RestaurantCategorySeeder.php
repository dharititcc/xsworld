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

        // if( $restaurants->count() )
        // {
        //     foreach( $restaurants as $restaurant )
        //     {
        //         if( !empty( $categories ) )
        //         {
        //             foreach( $categories as $category )
        //             {
        //                 $newCategory = $restaurant->categories()->create($category);

        //                 // attachments
        //                 $newCategory->attachment()->create([
        //                     'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
        //                     'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
        //                 ]);
        //             }
        //         }
        //     }
        // }

        if( $restaurants->count() )
        {
            foreach( $restaurants as $restaurant )
            {
                if( $restaurant->id == 1 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            $newCategory = $restaurant->categories()->create($category);

                            // attachments
                            $newCategory->attachment()->create([
                                'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                                'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                            ]);
                        }
                    }
                }

                if( $restaurant->id == 2 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[0]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 3 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 4 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            $newCategory = $restaurant->categories()->create($category);

                            // attachments
                            $newCategory->attachment()->create([
                                'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                                'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                            ]);
                        }
                    }
                }

                if( $restaurant->id == 5 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[0]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 6 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 7 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            $newCategory = $restaurant->categories()->create($category);

                            // attachments
                            $newCategory->attachment()->create([
                                'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                                'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                            ]);
                        }
                    }
                }

                if( $restaurant->id == 8 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[0]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 9 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 10 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            $newCategory = $restaurant->categories()->create($category);

                            // attachments
                            $newCategory->attachment()->create([
                                'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                                'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                            ]);
                        }
                    }
                }

                if( $restaurant->id == 11 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[0]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 12 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 13 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            $newCategory = $restaurant->categories()->create($category);

                            // attachments
                            $newCategory->attachment()->create([
                                'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                                'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                            ]);
                        }
                    }
                }

                if( $restaurant->id == 14 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[0]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 15 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 16 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 17 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 18 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 19 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }

                if( $restaurant->id == 20 )
                {
                    if( !empty( $categories ) )
                    {
                        $newCategory = $restaurant->categories()->create($categories[1]);

                        // attachments
                        $newCategory->attachment()->create([
                            'stored_name'   => str_replace(' ', '_', strtolower($category['name'])).'.jpg',
                            'original_name' => str_replace(' ', '_', strtolower($category['name'])).'.jpg'
                        ]);
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
