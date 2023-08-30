<?php

namespace Database\Seeders;

use App\Models\Order;
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
                'type'                  => Order::CART,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0],
                // 'credit_point'          => 0,
                // 'amount'                => 12,
                // 'transaction_id'        => 'transwdsgfdsfksdh',
                // 'total'                 => 12,
                // 'currency_id'           => 3,
                'order_details'         => [
                    [
                        'restaurant_item_id'    => 69,
                        'variation_id'          => 1,
                        'parent_item_id'        => null,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 421,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::MIXER,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 295,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ADDON,
                        'total'                 => 10
                    ]
                ]
            ],
            [

                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::CART,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0],
                // 'credit_point'          => 0,
                // 'amount'                => 12,
                // 'transaction_id'        => 'transwdsgfdsfksdh',
                // 'total'                 => 12,
                // 'currency_id'           => 3,
                'order_details'         => [
                    [
                        'restaurant_item_id'    => 69,
                        'variation_id'          => 1,
                        'parent_item_id'        => null,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 421,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::MIXER,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 295,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ADDON,
                        'total'                 => 10
                    ]
                ]
            ],
            [

                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::CART,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0],
                // 'credit_point'          => 0,
                // 'amount'                => 12,
                // 'transaction_id'        => 'transwdsgfdsfksdh',
                // 'total'                 => 12,
                // 'currency_id'           => 3,
                'order_details'         => [
                    [
                        'restaurant_item_id'    => 69,
                        'variation_id'          => 1,
                        'parent_item_id'        => null,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 421,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::MIXER,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 295,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ADDON,
                        'total'                 => 10
                    ]
                ]
            ],
            [

                'user_id'               => $user->id,
                'restaurant_id'         => 4,
                'pickup_point_id'       => 2,
                'pickup_point_user_id'  => 5,
                'type'                  => Order::CART,
                'status'                => Order::PENDNIG,
                'user_payment_method_id'=> $user->payment_methods->where('name', 'Cash')->pluck('id')[0],
                // 'credit_point'          => 0,
                // 'amount'                => 12,
                // 'transaction_id'        => 'transwdsgfdsfksdh',
                // 'total'                 => 12,
                // 'currency_id'           => 3,
                'order_details'         => [
                    [
                        'restaurant_item_id'    => 69,
                        'variation_id'          => 1,
                        'parent_item_id'        => null,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 421,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::MIXER,
                        'total'                 => 10
                    ],
                    [
                        'restaurant_item_id'    => 295,
                        'variation_id'          => null,
                        'parent_item_id'        => 69,
                        'quantity'              => 1,
                        'price'                 => 10,
                        'type'                  => RestaurantItem::ADDON,
                        'total'                 => 10
                    ]
                ]
            ],
        ];

        foreach( $orders as $key => $order )
        {
            $orderItems     = $order['order_details'];

            unset($order['order_details']);

            $newOrder = Order::create($order);

            // $newOrder->items()->create($orderItems);
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
