<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantItemSeeder extends Seeder
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

        $drinksArr = [
            [
                'name'          => 'Brandy & Weinbrand',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Gin',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Liquers',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Rum',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Vodka',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Old Fashioned',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Negroni',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Whisky Sour',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Dry Martini',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Manhattan',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Lager',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Ale',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Stout',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Porter',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Brown Ale',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Red Wine',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'White Wine',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Rose Wine',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Sparkling Wine',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Dessert Wine',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ]
        ];

        $foodArr = [
            [
                'name'          => 'Portuguese Chiken',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35
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
                        // drink items
                        if( $category->name == 'Drinks' )
                        {
                            if( !empty( $drinksArr ) )
                            {
                                $counter = 1;
                                foreach( $drinksArr as $item )
                                {
                                    $item['restaurant_id'] = $restaurant->id;
                                    $item['category_id'] = $category->id;
                                    $newItem = RestaurantItem::create($item);

                                    // Logic for the attachment
                                    $newItem->attachment()->create([
                                        'stored_name'   => $counter.'.jpg',
                                        'original_name' => $counter.'.jpg'
                                    ]);

                                    // Item variations
                                    $variationArr = [
                                        [
                                            'name'  => 'Single Shot',
                                            'price' => '20',
                                        ],
                                        [
                                            'name'  => 'Double Shot',
                                            'price' => '40',
                                        ],
                                        [
                                            'name'  => 'Bottle',
                                            'price' => '100',
                                        ],
                                        [
                                            'name'  => 'Glass',
                                            'price' => '10',
                                        ],
                                        [
                                            'name'  => 'Jug',
                                            'price' => '120',
                                        ],
                                        [
                                            'name'  => 'Pint',
                                            'price' => '150',
                                        ]
                                    ];

                                    foreach( $variationArr as $variation )
                                    {
                                        $newItem->variations()->create($variation);
                                    }

                                    $counter++;
                                }
                            }
                        }

                        // food items
                        if( $category->name == 'Food' )
                        {
                        }
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
