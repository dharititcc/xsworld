<?php namespace App\Repositories\Traits;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\Category;
use App\Models\CustomerTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSplit;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantPickupPoint;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait OrderFlow
{
    /**
     * Method addTocart
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function addTocart(array $data)
    {
        $user       = isset( $data['order']['user_id'] ) ? User::findOrFail($data['order']['user_id']) : auth()->user();
        $order      = isset( $data['order'] ) ? $data['order'] : [];
        $orderItems = isset( $data['order_items'] ) ? $data['order_items'] : [];

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant']);

        $latestCart = $user->latest_cart;

        if( isset( $latestCart->id ) && ($latestCart->restaurant->id ==  $order['restaurant_id']) )
        {
            // check restaurant id available in the cart
            return $this->checkSameRestaurantOrder($user, $latestCart, $orderItems);
        }
        else
        {
            // new order
            return $this->createOrder($user, $order, $orderItems);
        }

        throw new GeneralException('Order request is invalid.');
    }

    /**
     * Method checkSameRestaurantOrder
     *
     * @param User $user [explicite description]
     * @param Order $order [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    public function checkSameRestaurantOrder(User $user, Order $order, array $orderItems): Order
    {
        $isFoodAvailable    = 0;
        $parentCategory     = null;
        // check if order request is available
        if( isset( $order->id ) && !empty($orderItems) )
        {
            if( !empty($orderItems) )
            {
                foreach( $orderItems as $item )
                {
                    $category           = Category::with(['children_parent'])->find($item['category_id']);
                    $parentCategory     = $category->parent_id;
                    $isFoodAvailable    = $category->children_parent->name == 'Food' ? 1 : 0;

                    // order split create
                    $checkExistOrderSplit = $order->order_splits()->where('is_food', $isFoodAvailable)->first();

                    if( !isset($checkExistOrderSplit->id) )
                    {
                        // create order split row
                        $checkExistOrderSplit = $this->createOrderSplit([
                            'order_id'      => $order->id,
                            'is_food'       => $isFoodAvailable
                        ]);
                    }

                    // make proper item array for the table
                    $itemArr = [
                        'restaurant_item_id'    => $item['item_id'],
                        'category_id'           => $parentCategory,
                        'order_split_id'        => $checkExistOrderSplit->id,
                        'price'                 => $item['price'],
                        'quantity'              => $item['quantity'],
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => $item['quantity'] * $item['price']
                    ];

                    if( isset( $item['variation'] ) && !empty( $item['variation'] ) )
                    {
                        $variationArr = [
                            'restaurant_item_id'    => $item['item_id'],
                            'category_id'           => $parentCategory,
                            'order_split_id'        => $checkExistOrderSplit->id,
                            'parent_item_id'        => null,
                            'variation_id'          => $item['variation']['id'],
                            'quantity'              => $item['variation']['quantity'],
                            'price'                 => $item['variation']['price'],
                            'type'                  => RestaurantItem::ITEM,
                            'total'                 => $item['variation']['price'] * $item['variation']['quantity']
                        ];

                        // add variation in the order items table
                        $newOrderItem = $this->createOrderItem($order, $variationArr);
                    }
                    else
                    {
                        $newOrderItem = $this->createOrderItem($order, $itemArr);
                    }

                    // add item in the order items table

                    // make proper mixer data for the table
                    if( isset( $item['mixer'] ) && !empty( $item['mixer'] ) )
                    {
                        $mixerArr = [
                            'restaurant_item_id'=> $item['mixer']['id'],
                            'order_split_id'    => $checkExistOrderSplit->id,
                            'parent_item_id'    => $newOrderItem->id,
                            'price'             => $item['mixer']['price'],
                            'type'              => RestaurantItem::MIXER,
                            'quantity'          => $item['mixer']['quantity'],
                            'total'             => $item['mixer']['quantity'] * $item['mixer']['price']
                        ];

                        // add mixer in the order items table
                        $mixerItem = $this->createOrderItem($order, $mixerArr);
                    }

                    // make proper data for addons
                    if( isset( $item['addons'] ) && !empty( $item['addons'] ) )
                    {
                        $addons = $item['addons'];

                        if( !empty( $addons ) )
                        {
                            foreach( $addons as $addon )
                            {
                                $addonData = [
                                    'order_split_id'        => $checkExistOrderSplit->id,
                                    'restaurant_item_id'    => $addon['id'],
                                    'parent_item_id'        => $newOrderItem->id,
                                    'price'                 => $addon['price'],
                                    'type'                  => RestaurantItem::ADDON,
                                    'quantity'              => $addon['quantity'],
                                    'total'                 => $addon['quantity'] * $addon['price']
                                ];

                                // add addon in the order items table
                                $addonItem = $this->createOrderItem($order, $addonData);
                            }
                        }
                    }
                }
            }

            $order->refresh();
            // if( $this->checkOrderCategoryType($order->order_items) )
            // {
            //     // update order category to 1
            //     $order_category_type = 1;
            // }
            // else
            // {
            //     // update order category to 0
            //     $order_category_type = 0;
            // }
            $order_category_type = $this->checkOrderCategoryType($order->order_items);
            $order->loadMissing(['items']);
            $order->update(['total' => $order->items->sum('total'),'order_category_type' => $order_category_type]);

            return $order;
        }

        throw new GeneralException('Order could not be updated.');
    }

    /**
     * Method createOrderSplit
     *
     * @param array $data [explicite description]
     *
     * @return OrderSplit
     */
    public function createOrderSplit(array $data): OrderSplit
    {
        return OrderSplit::create($data);
    }

    /**
     * Method createOrder
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    public function createOrder(User $user, array $data, array $orderItems): Order
    {
        $restaurant                     = Restaurant::find($data['restaurant_id']);
        $order['user_id']               = $user->id;
        $order['restaurant_id']         = $restaurant->id;
        $order['currency_id']           = $restaurant->currency_id;
        $order['waiter_id']             = access()->isWaiter() ? auth()->user()->id : null;
        $order['restaurant_table_id']   = isset($data['restaurant_table_id']) ? $data['restaurant_table_id'] : null;

        if($order['restaurant_table_id']) {
            $order['status']    = Order::WAITER_PENDING;
        }

        $newOrder = Order::create($order);

        $newOrder->refresh();

        if($order['restaurant_table_id']) {
            CustomerTable::where('user_id' , $user->id)->where('restaurant_table_id',$order['restaurant_table_id'])->update(['order_id' => $newOrder->id]);
        }

        return $this->checkSameRestaurantOrder($user, $newOrder, $orderItems);
    }

    /**
     * Method updateCart
     *
     * @param Order $order [explicite description]
     * @param array $data [explicite description]
     *
     * @return Order
     */
    public function updateCart(Order $order, array $data): Order
    {
        /*[
            "order_id" => 10
            "item_id" => 38
            "quantity" => 5
        ];*/
        $user       = auth()->user();
        $orderItem  = $order->order_items->where('id', $data['item_id'])->first();

        $orderItem->loadMissing(['addons', 'mixer']);

        // update order item quantity
        $orderItem->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $orderItem->price]);

        // check if that item has any addons
        if( $orderItem->addons->count() )
        {
            foreach( $orderItem->addons as $addon )
            {
                $addon->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $addon->price]);
            }
        }

        // check if that item has any mixer
        if( isset( $orderItem->mixer->id ) )
        {
            $orderItem->mixer->update(['quantity' => $data['quantity'], 'total' => $data['quantity'] * $orderItem->mixer->price]);
        }

        $order->refresh();
        $order->loadMissing(['items']);
        $order->update(['total' => $order->items->sum('total')]);

        return $order;
    }

    /**
     * Method createOrderItem
     *
     * @param Order $order [explicite description]
     * @param array $data [explicite description]
     *
     * @return OrderItem
     */
    public function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    /**
     * Method checkOrderCategoryType
     *
     * @param Collection $items [explicite description]
     *
     * @return int
     */
    public function checkOrderCategoryType(Collection $items): int
    {
        $isOrderCategoryType = 0;
        $isDrinkCategory = 0;
        $isFoodCategory = 0;
        if( $items->count() )
        {
            foreach( $items as $item )
            {
                $item->loadMissing(['restaurant_item', 'restaurant_item.category.children_parent']);

                $parentCategory = $item->restaurant_item->category->children_parent;

                if( $parentCategory->name == 'Food' )
                {
                    $isFoodCategory = 1;
                }

                if( $parentCategory->name == 'Drinks' )
                {
                    $isDrinkCategory = 1;
                }
            }
        }

        if( $isDrinkCategory === 1 && $isFoodCategory === 1 )
        {
            $isOrderCategoryType = 2;
        }
        else if( $isDrinkCategory !== 1 && $isFoodCategory === 1 )
        {
            $isOrderCategoryType = 1;
        }
        else
        {
            $isOrderCategoryType = 0;
        }

        return $isOrderCategoryType;
    }

    /**
     * Method placeOrder
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function placeOrder(array $data): Order
    {
        $card_id            = $data['card_id'] ?? null;
        $credit_amount      = $data['credit_amount'] ? $data['credit_amount'] : null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::with(['restaurant'])->findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $devices            = $user->devices()->pluck('fcm_token')->toArray();
        $pickup_point_id    = '';

        if($order->order_category_type == Order::BOTH) {
            $pickup_point_id    = $this->randomPickpickPoint($order);
        } else {
            $pickup_point_id    = isset($data['pickup_point_id']) ? RestaurantPickupPoint::findOrFail($data['pickup_point_id']) : null;
        }

        // handle if pickup point exist or bartender associated
        if( !isset( $pickup_point_id->id ) && !isset( $pickup_point_id->user_id ) )
        {
            throw new GeneralException('There is no pickup point or bartender assiociated.');
        }

        $userCreditAmountBalance = $user->credit_amount;
        $updateArr         = [];
        $paymentArr        = [];

        if(isset($order->id))
        {
            if($order->total <= $credit_amount)
            {
                $updateArr = [
                    'type'                  => Order::ORDER,
                    'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
                ];
                $remaingAmount = $userCreditAmountBalance - $credit_amount;

                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $remaingAmount]);
            }


            if( $order->total != $credit_amount )
            {
                $paymentArr = [
                    'amount'        => number_format($amount, 2) * 100,
                    'currency'      => $order->restaurant->currency->code,
                    'customer'      => $user->stripe_customer_id,
                    'capture'       => false,
                    'source'        => $card_id,
                    'description'   => $order->id
                ];

                $stripe         = new Stripe();
                $payment_data   = $stripe->createCharge($paymentArr);

                $updateArr = [
                    'type'                  => Order::ORDER,
                    'card_id'               => $card_id,
                    'charge_id'             => $payment_data->id,
                    'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
                    'amount'                => $amount,
                ];
                $remaingAmount = $userCreditAmountBalance - $credit_amount;

                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $remaingAmount]);
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);

        $text               = $order->restaurant->name. ' is processing your order';
        $title              = $text;
        $message            = "Your Order is #".$order->id." placed";
        $orderid            = $order->id;
        $send_notification  = sendNotification($title,$message,$devices,$orderid);

        $bartitle           = "Order is placed by Customer";
        $barmessage         = "Order is #".$order->id." placed by customer";
        $bardevices         = $pickup_point_id ? $order->pickup_point_user->devices()->pluck('fcm_token')->toArray() : [];
        if(!empty( $bardevices )) {
            $bar_notification   = sendNotification($bartitle,$barmessage,$bardevices,$orderid);
        }

        // send notification to waiter if table order
        if( isset( $table_id ) )
        {
            $order->loadMissing([
                'restaurant',
                'restaurant.waiters'
            ]);

            $getcusTbl = CustomerTable::where('user_id' , $user->id)->where('restaurant_table_id',$table_id)->where('order_id',$data['order_id'])->first();
            if($getcusTbl) {
                throw new GeneralException('Already table allocated');
                $customerTbl = 0;
            } else {
                $customerTbl = CustomerTable::updateOrCreate([
                    'restaurant_table_id' => $table_id,
                    'user_id'       => $user->id,
                    'order_id'      => $data['order_id'],
                ],
                // [
                //     'waiter_id'     => $data['waiter_id']
                // ]
                );
            }

            $waiterDevices = [];
            $waiters = $order->restaurant->waiters()->with(['user', 'user.devices'])->get();

            if( $waiters->count() )
            {
                foreach( $waiters as $waiter )
                {
                    $WaiterDevicesTokensArr = $waiter->user->devices->pluck('fcm_token')->toArray();
                    // $waiterDevices = array_merge($waiterDevices, );
                    if( !empty( $WaiterDevicesTokensArr ) )
                    {
                        foreach( $WaiterDevicesTokensArr as $token )
                        {
                            $waiterDevices[] = $token;
                        }
                    }
                }
            }

            if( !empty( $waiterDevices ) )
            {
                $waiterTitle    = 'New order placed by customer';
                $waiterMessage  = "Order is #{$order->id} placed by customer";
                sendNotification($waiterTitle, $waiterMessage, $waiterDevices, $orderid);
            }
        }

        return $order;
    }

    /**
     * Method randomPickpickPoint
     *
     * @param Order $order [explicite description]
     *
     * @return null|RestaurantPickupPoint
     */
    public function randomPickpickPoint(Order $order): ?RestaurantPickupPoint
    {
        $restaurant_id = $order->restaurant_id;
        $pickup_point_id = RestaurantPickupPoint::where(['restaurant_id' => $restaurant_id , 'type' => 2, 'status' => RestaurantPickupPoint::ONLINE, 'is_table_order' => 1])->inRandomOrder()->first();
        return $pickup_point_id;
    }

    /**
     * Method getKitchenConfirmedOrders
     *
     * @return Collection
     */
    public function getKitchenConfirmedOrders(): Collection
    {
        $kitchen = auth()->user();

        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, OrderSplit::PENDING, 'desc');
        return $query->get();
    }

    /**
     * Method getKitchenOrdersQuery
     *
     * @param User $kitchen [explicite description]
     * @param int $status [explicite description]
     * @param string $sort [explicite description]
     *
     * @return Builder
     */
    public function getKitchenOrdersQuery(User $kitchen, int $status, string $sort = 'asc'): Builder
    {
        return $query = Order::query()
                        ->with(
                            [
                                'restaurant',
                                'restaurant_table',
                                'restaurant.country',
                                'restaurant.currency',
                                'user',
                                'user.attachment',
                                'restaurant_pickup_point',
                                'pickup_point_user',
                                'pickup_point_user.attachment',
                                'restaurant_pickup_point.attachment',
                                'order_split_food',
                                'order_split_food.items',
                                'order_split_food.items.restaurant_item',
                            ]
                        )
                        ->whereHas('order_split_food', function($query) use($status){
                            if( is_array($status) )
                            {
                                $query->whereIn('status', $status);
                            }
                            else
                            {
                                $query->whereIn('status', [$status]);
                            }
                        })
                        ->where('restaurant_id', $kitchen->restaurant_kitchen->restaurant_id)
                        ->orderBy('id', $sort);
    }

    /**
     * Method getKitchenOrderCollections
     *
     * @return Collection
     */
    public function getKitchenOrderCollections(): Collection
    {
        $kitchen = auth()->user();

        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, OrderSplit::READYFORPICKUP, 'asc');
        return $query->get();
    }

    /**
     * Method getCompletedKitchenOrders
     *
     * @return Collection
     */
    public function getCompletedKitchenOrders(): Collection
    {
        $kitchen = auth()->user();

        // load restaurant relationship
        $kitchen->loadMissing(['restaurant_kitchen']);

        $query = $this->getKitchenOrdersQuery($kitchen, OrderSplit::KITCHEN_CONFIRM, 'desc');
        return $query->get();
    }
}