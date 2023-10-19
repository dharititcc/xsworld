<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class OrderRepository.
*/
class OrderRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = Order::class;

    /**
     * Method addTocart
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function addTocart(array $data)
    {
        $user       = auth()->user();
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
    private function checkSameRestaurantOrder(User $user, Order $order, array $orderItems): Order
    {
        // check if order request is available
        if( isset( $order->id ) && !empty($orderItems) )
        {
            if( !empty($orderItems) )
            {
                foreach( $orderItems as $item )
                {
                    // make proper item array for the table
                    $itemArr = [
                        'restaurant_item_id'    => $item['item_id'],
                        'category_id'           => $item['category_id'],
                        'price'                 => $item['price'],
                        'quantity'              => $item['quantity'],
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => $item['quantity'] * $item['price']
                    ];

                    if( isset( $item['variation'] ) && !empty( $item['variation'] ) )
                    {
                        $variationArr = [
                            'restaurant_item_id'    => $item['item_id'],
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
            $order->loadMissing(['items']);
            $order->update(['total' => $order->items->sum('total')]);

            return $order;
        }

        throw new GeneralException('Order could not be updated.');
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
    private function createOrder(User $user, array $data, array $orderItems): Order
    {
        $restaurant = Restaurant::find($data['restaurant_id']);
        $order['user_id'] = $user->id;
        $order['restaurant_id'] = $restaurant->id;
        $order['currency_id'] = $restaurant->currency_id;

        // dd($order);
        $newOrder = Order::create($order);

        $newOrder->refresh();

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
    private function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    /**
     * Method getCartdata
     *
     * @return Order
     */
    public function getCartdata(): ?Order
    {
        $user        = auth()->user();

        $user->loadMissing(
            [
                'latest_cart',
                'latest_cart.order_items',
                'latest_cart.restaurant',
                'latest_cart.restaurant.pickup_points' => function($query)
                {
                    return $query->status(1);
                }
            ]
        );

        return $user->latest_cart;
    }

    /**
     * Method getCartdata
     *
     * @return Order
     */
    public function getCurrentOrder(): ?Order
    {
        $user        = auth()->user();

        $user->loadMissing(['latest_order', 'orders']);

        return $user->orders()->whereIn('status', [Order::ACCEPTED, Order::PENDNIG, Order::COMPLETED])->orderBy('id', 'desc')->first();
    }

    /**
     * Method getOrderdata
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    function getOrderdata(array $data) : array
    {
        $page   = isset($data['page']) ? $data['page'] : 1;
        $limit  = isset($data['limit']) ? $data['limit'] : 10;
        $text   = isset($data['text']) ? $data['text'] : null;

        $user   = auth()->user();

        $user->loadMissing([
            'orders'
        ]);

        $query = $user
        ->orders()
        ->where('type', Order::ORDER)
        ->with([
            'user',
            'reviews',
            'order_items',
            'order_mixer',
            'restaurant'
        ]);

        if( $text )
        {
            $query->where(function($innerQuery) use($text)
            {
                $innerQuery->where('id', $text);
                $innerQuery->orWhereHas('restaurant', function($resQuery) use($text)
                {
                    $resQuery->where('name', 'like', "%{$text}%");
                });
            });
        }
        $total = $query->count();
        $query->limit($limit)->offset(($page - 1) * $limit)->orderBy('id','desc');
        $data = $query->get();
        if( $data->count() )
        {
            $data = [
                'total_orders'   => $total,
                'orders'         => $data
            ];
            return $data;
        }

        throw new GeneralException('There is no order found.');
    }

    /**
     * Method getCartCount
     *
     * @return array
     */
    function getCartCount() : array
    {
        $user       = auth()->user();

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant', 'latest_cart.order_items']);

        $cart       = [
            'cart_count'    => isset($user->latest_cart->order_items) ? $user->latest_cart->order_items->sum('quantity') : 0,
            'restaurant_id' => $user->latest_cart->restaurant->id ?? 0,
            'order_id'      => $user->latest_cart->id ?? 0
        ];

        return $cart;
    }

    /**
     * Method deleteItem
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function deleteItem(array $data): Order
    {
        $order_item_id  = $data['order_item_id'] ? $data['order_item_id'] : null;
        $orderItem      = OrderItem::with(['addons','mixer', 'order'])->findOrFail($order_item_id);
        $order          = $orderItem->order;

        if($orderItem->parent_item_id == null)
        {
            $data = $orderItem->addons()->delete();
            $data = $orderItem->mixer()->delete();
            $orderItem->delete();
        }
        else
        {
            $orderItem->delete();
        }

        $order->refresh();
        $order->loadMissing(['items']);
        $order->update(['total' => $order->items->sum('total')]);

        return $order;
    }

    /**
     * Method deleteCart
     *
     * @param array $data [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    function deleteCart(array $data): bool
    {
        $user       = auth()->user();
        $order_id   = $data['order_id'] ? $data['order_id'] : null;
        $order      = Order::where(['id' => $order_id, 'user_id' => $user->id])->first();

        if(isset($order->id))
        {
            // delete order items
            $order->items()->delete();

            // delete order
            return $order->delete();
        }

        throw new GeneralException('Order is not found.');
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
        $card_id            = $data['card_id'] ? $data['card_id'] : null;
        $credit_amount      = $data['credit_amount'] ? $data['credit_amount'] : null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        $pickup_point_id    = $data['pickup_point_id'] ? $data['pickup_point_id'] : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::findOrFail($data['order_id']);
        $user               = auth()->user();
        $devices            = $user->devices()->pluck('fcm_token')->toArray();

        $updateArr         = [];
        $paymentArr        = [];

        if(isset($order->id))
        {
            if($order->total == $credit_amount)
            {
                $updateArr = [
                    'type'                  => Order::ORDER,
                    'pickup_point_id'       => $pickup_point_id,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => $table_id
                ];
            }


            if( $order->total != $credit_amount )
            {
                $paymentArr = [
                    'amount'        => $amount * 100,
                    'currency'      => $order->restaurant->currency->code,
                    'customer'      => $user->stripe_customer_id,
                    'capture'       => false,
                    'source'        => $card_id,
                    'description'   => $order->id
                ];

                $stripe         = new Stripe();
                $payment_data   = $stripe->createCharge($paymentArr);

                $updateArr = [
                    'type'              => Order::ORDER,
                    'card_id'           => $card_id,
                    'charge_id'         => $payment_data->id,
                    'pickup_point_id'   => $pickup_point_id,
                    'credit_amount'     => $credit_amount,
                    'restaurant_table_id'   => $table_id
                ];
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);
        // TODO:
        $title      = "Preparing Your order";
        $message    = "Your Order is ".$order->id." placed";

        //$send_notification = sendNotification($title,$message,$devices);

        return $order;
    }

    /**
     * Method updateOrderStatus
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function updateOrderStatus(array $data) : Order
    {
        $order_id          = $data['order_id'] ? $data['order_id'] : null;
        $status            = $data['status'] ? $data['status'] : null;
        $order             = Order::findOrFail($order_id);
        $updateArr         = [];

        if(isset($order->id))
        {
            if($status == Order::CUSTOMER_CANCELED)
            {
                $updateArr['cancel_date']   = Carbon::now();
                $updateArr['status']        = $status;
            }
            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);

        return $order;
    }

    /**
     * Method ReviewOrder
     *
     * @param array $data [explicite description]
     *
     * @return OrderReview
     * @throws \App\Exceptions\GeneralException
     */
    function ReviewOrder(array $data) : OrderReview
    {
        $order_id   = isset($data['order_id']) ? $data['order_id'] : null;
        $rating     = isset($data['rating']) ? $data['rating'] : null;
        $comment    = isset($data['comment']) ? $data['comment'] : null;
        $reviewArr  = [];

        $reviewArr  = [
            'order_id'  => $order_id,
            'rating'    => $rating,
            'comment'   => $comment
        ];

        $order   = Order::where('id' , $order_id)->first();

        if(isset($order->id))
        {
            $reviewOrder = OrderReview::create($reviewArr);
            return $reviewOrder;
        }

        throw new GeneralException('Order is not found.');
    }

    function GetKitchenOrders(array $data,$is_history=0)
    {
        $orders = Order::whereIn('restaurant_id',$data);
        if($is_history === 0) {
            $orderTbl = $orders->where('status',Order::ACCEPTED)->get();
        } else {
            $orderTbl = $orders->whereIn('status',[Order::COMPLETED,Order::FULL_REFUND, Order::PARTIAL_REFUND, Order::RESTAURANT_CANCELED, Order::CUSTOMER_CANCELED])->get();
        }
        if($orderTbl)
        {
            return $orderTbl;
        } else {
            throw new GeneralException('Order is not found.');
        }
    }
}