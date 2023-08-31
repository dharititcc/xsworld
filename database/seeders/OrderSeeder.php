<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
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

        // get customer
        $user = User::with(['payment_methods'])->find(3); // milan@yopmail.com

        $orders = [
            [
                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::ORDER,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0]
            ],
            [
                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::ORDER,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0]
            ],
            [
                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::ORDER,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0]
            ],
            [
                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::ORDER,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0]
            ],
        ];

        foreach( $orders as $order )
        {
            $newOrder = Order::create($order);
            // $restaurant = Restaurant::find($order['restaurant_id']);
            // dump($order);
            // load restaurant relationship
            $newOrder->loadMissing(['restaurant', 'restaurant.main_categories']);

            // $restaurant->loadMissing(['main_categories']);

            $restaurant = $newOrder->restaurant;

            $orderDetails = [];

            /*App\Models\Restaurant {#7312
                id: 4,
                currency_id: 3,
                country_id: 3,
                name: "Butterfly 73",
                latitude: -33.8789136,
                longitude: 151.145561,
                street1: "4/256 Crown St",
                street2: null,
                city: "Darlinghurst",
                state: "NSW",
                postcode: "2010",
                address: null,
                phone: null,
                specialisation: "Bar",
                status: 1,
                created_at: "2023-08-31 02:51:05",
                updated_at: "2023-08-31 02:51:05",
            }*/

            // fetch categories of that restaurant
            // $restaurant->main_categories
            /*Illuminate\Database\Eloquent\Collection {#7338
                all: [
                  App\Models\Category {#7300
                    id: 5,
                    name: "Food",
                    parent_id: null,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                  App\Models\Category {#7329
                    id: 6,
                    name: "Drinks",
                    parent_id: null,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                ],
            }*/

            // get single category of that restaurant
            $drinkCategory = $restaurant->main_categories->where('name', 'Drinks')->first();

            /*App\Models\Category {#7289
                id: 6,
                name: "Drinks",
                parent_id: null,
                restaurant_id: 4,
                status: 1,
                created_at: "2023-08-31 02:51:05",
                updated_at: "2023-08-31 02:51:05",
                deleted_at: null,
            }*/

            // find children of that category
            /*Illuminate\Database\Eloquent\Collection {#7373
                all: [
                  App\Models\Category {#7314
                    id: 43,
                    name: "Spirits",
                    parent_id: 6,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                  App\Models\Category {#7362
                    id: 44,
                    name: "Cocktails",
                    parent_id: 6,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                  App\Models\Category {#7329
                    id: 45,
                    name: "Beers",
                    parent_id: 6,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                  App\Models\Category {#7334
                    id: 46,
                    name: "Wines",
                    parent_id: 6,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                  App\Models\Category {#7370
                    id: 47,
                    name: "Champagne",
                    parent_id: 6,
                    restaurant_id: 4,
                    status: 1,
                    created_at: "2023-08-31 02:51:05",
                    updated_at: "2023-08-31 02:51:05",
                    deleted_at: null,
                  },
                ],
            }*/
            $childrenCategories = $drinkCategory->children;


            // get single child category
            $singleChildCategory = $childrenCategories->where('name', 'Spirits')->first();
            $singleChildCategory->loadMissing(['addons', 'mixers']);
            /*App\Models\Category {#7432
                id: 43,
                name: "Spirits",
                parent_id: 4,
                restaurant_id: 4,
                status: 1,
                created_at: "2023-08-31 02:51:05",
                updated_at: "2023-08-31 02:51:05",
                deleted_at: null,
            }*/


            // fetch all the items of spirits category
            $item = $singleChildCategory->items->random(1)->first();
            /*App\Models\RestaurantItem {#7488
                id: 69,
                name: "Rum Range",
                description: null,
                restaurant_id: 4,
                category_id: 43,
                parent_id: null,
                type: 2,
                is_variable: 1,
                price: "85.00",
                is_featured: 1,
                created_at: "2023-08-31 02:51:06",
                updated_at: "2023-08-31 02:51:06",
                deleted_at: null,
            }*/

            // fetch variations of the item if any
            if( $item->is_variable )
            {
                /* Illuminate\Database\Eloquent\Collection {#7324
                    all: [
                      App\Models\RestaurantVariation {#7311
                        id: 63,
                        name: "Single Shot",
                        price: "10.00",
                        restaurant_item_id: 70,
                        created_at: "2023-08-31 02:51:06",
                        updated_at: "2023-08-31 02:51:06",
                        deleted_at: null,
                      },
                      App\Models\RestaurantVariation {#7322
                        id: 64,
                        name: "Double Shot",
                        price: "20.00",
                        restaurant_item_id: 70,
                        created_at: "2023-08-31 02:51:06",
                        updated_at: "2023-08-31 02:51:06",
                        deleted_at: null,
                      },
                    ],
                }*/

                $variation = $item->variations->random(1)->first();

                $orderDetails[] = [
                    'restaurant_item_id'    => $variation->restaurant_item_id,
                    'variation_id'          => $variation->id,
                    'parent_item_id'        => $item->id,
                    'quantity'              => 1,
                    'price'                 => $variation->price,
                    'type'                  => RestaurantItem::ITEM,
                    'total'                 => 1 * $variation->price
                ];
            }

            // add addons
            $addons = $singleChildCategory->addons->random(2);

            if($addons->count())
            {
                foreach( $addons as $addon )
                {
                    $orderDetails[] = [
                        'restaurant_item_id'    => $addon->id,
                        'variation_id'          => null,
                        'parent_item_id'        => null,
                        'quantity'              => 2,
                        'price'                 => $addon->price,
                        'type'                  => RestaurantItem::ADDON,
                        'total'                 => 2 * $addon->price
                    ];
                }
            }

            // add mixer
            $mixer = $singleChildCategory->mixers->random(1)->first();

            $orderDetails[] = [
                'restaurant_item_id'    => $mixer->id,
                'variation_id'          => null,
                'parent_item_id'        => null,
                'quantity'              => 2,
                'price'                 => $mixer->price,
                'type'                  => RestaurantItem::MIXER,
                'total'                 => 2 * $mixer->price
            ];

            if( !empty( $orderDetails ) )
            {
                foreach( $orderDetails as $order_item )
                {
                    $newOrder->items()->create($order_item);
                }
            }

            $newOrder->refresh();

            // get all the order items
            $orderTotal = $newOrder->items->sum('total');
            $currencyId = $newOrder->restaurant->currency->id;

            $newOrder->update([
                'total'         => $orderTotal,
                'currency_id'   => $currencyId
            ]);
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
