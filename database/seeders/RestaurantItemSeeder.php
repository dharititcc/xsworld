<?php

namespace Database\Seeders;

use App\Models\Item;
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

        $restaurantitemArr = [
            [
                'name'          => 'Brandy & Weinbrand',
                // 'item_type_id'  => 1,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Gin',
                // 'item_type_id'  => 1,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Liquers',
                // 'item_type_id'  => 1,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Rum',
                // 'item_type_id'  => 1,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Vodka',
                // 'item_type_id'  => 1,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Old Fashioned',
                // 'item_type_id'  => 2,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Negroni',
                // 'item_type_id'  => 2,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Whisky Sour',
                // 'item_type_id'  => 2,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Dry Martini',
                // 'item_type_id'  => 2,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Manhattan',
                // 'item_type_id'  => 2,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Lager',
                // 'item_type_id'  => 3,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Ale',
                // 'item_type_id'  => 3,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Stout',
                // 'item_type_id'  => 3,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Porter',
                // 'item_type_id'  => 3,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Brown Ale',
                // 'item_type_id'  => 3,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Red Wine',
                // 'item_type_id'  => 4,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'White Wine',
                // 'item_type_id'  => 4,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Rose Wine',
                // 'item_type_id'  => 4,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Sparkling Wine',
                // 'item_type_id'  => 4,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
            ],
            [
                'name'          => 'Dessert Wine',
                // 'item_type_id'  => 4,
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 10
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
                        if( $category->name == 'Drinks' )
                        {
                            if( !empty( $restaurantitemArr ) )
                            {
                                $counter = 1;
                                foreach( $restaurantitemArr as $item )
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
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
