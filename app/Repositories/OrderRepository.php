<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\CustomerTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use App\Models\PickupPoint;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantWaiter;
use App\Models\User;
use App\Models\UserPaymentMethod;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations\Items;
use Stripe\Source;
use Stripe\Token;

/**
 * Class OrderRepository.
*/
class OrderRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = Order::class;

    /** @var \App\Repositories\UserRepository */
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

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
                    return $query->status(PickupPoint::ONLINE);
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
        // dd($user);

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
        $card_id            = $data['card_id'] ?? null;
        $credit_amount      = $data['credit_amount'] ? $data['credit_amount'] : null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        $pickup_point_id    = $data['pickup_point_id'] ? PickupPoint::findOrFail($data['pickup_point_id']) : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $devices            = $user->devices()->pluck('fcm_token')->toArray();

        $updateArr         = [];
        $paymentArr        = [];

        if(isset($order->id))
        {
            if($order->total == $credit_amount)
            {
                $updateArr = [
                    'type'                  => Order::ORDER,
                    'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
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
                    'type'                  => Order::ORDER,
                    'card_id'               => $card_id,
                    'charge_id'             => $payment_data->id,
                    'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
                ];
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);

        $title              = "Preparing Your order";
        $message            = "Your Order is #".$order->id." placed";
        $orderid            = $order->id;
        $send_notification  = sendNotification($title,$message,$devices,$orderid);

        $bartitle           = "Order is placed by Customer";
        $barmessage         = "Order is #".$order->id." placed by customer";
        $bardevices         = $order->pickup_point_user->devices()->pluck('fcm_token')->toArray();
        $bar_notification   = sendNotification($bartitle,$barmessage,$bardevices,$orderid);

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
        $user              = auth()->user();
        $devices           = $user->devices()->pluck('fcm_token')->toArray();

        if(isset($order->id))
        {
            if($status == Order::CUSTOMER_CANCELED)
            {
                $updateArr['cancel_date']   = Carbon::now();
                $updateArr['status']        = $status;
            }

            $order->update($updateArr);

            // $title      = "Order is cancelled";
            // $message    = "Your Order is #".$order->id." cancelled";

            // $send_notification = sendNotification($title,$message,$devices,$order_id);
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
        $order      = isset($data['order_id']) ? Order::findOrFail($data['order_id']) : null;
        $rating     = isset($data['rating']) ? $data['rating'] : null;
        $comment    = isset($data['comment']) ? $data['comment'] : null;
        $reviewArr  = [];

        $reviewArr  = [
            'order_id'      => $order->id,
            'restaurant_id' => $order->restaurant->id,
            'rating'        => $rating,
            'comment'       => $comment
        ];

        if(isset($order->id))
        {
            $reviewOrder = OrderReview::create($reviewArr);
            return $reviewOrder;
        }

        throw new GeneralException('Order is not found.');
    }

    /**
     * Method GetKitchenOrders
     *
     * @param array $data [explicite description]
     * @param $is_history=0 $is_history [explicite description]
     *
     * @return void
     */
    function GetKitchenOrders(array $data,$is_history=0)
    {
        $orders = Order::whereIn('restaurant_id',$data);
        if($is_history === 0) {
            $orderTbl = $orders->whereIn('status',[Order::ACCEPTED,Order::WAITER_PENDING])->where('type',Order::ORDER)->get();
        } else {
            $orderTbl = $orders->whereIn('status',[Order::COMPLETED,Order::FULL_REFUND, Order::PARTIAL_REFUND, Order::RESTAURANT_CANCELED, Order::CUSTOMER_CANCELED, Order::KITCHEN_CONFIRM])->where('type',Order::ORDER)->get();
        }
        if($orderTbl)
        {
            return (object)$orderTbl;
        } else {
            throw new GeneralException('Order is not found.');
        }
    }

    /**
     * Method getBarCollections
     *
     * @param array $data [explicite description]
     *
     * @return Collection
     */
    public function getBarCollections(array $data) : Collection
    {
        $orders = Order::whereIn('restaurant_id',$data)
        ->where('type', Order::ORDER)
        ->where('status', [Order::READYFORPICKUP])
        ->orderByDesc('id')
        ->get();

        return $orders;
    }

    /**
     * Method getOrderById
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getOrderById($id)
    {
        $order = Order::findOrFail($id);
        return $order;
    }


    /**
     * Method updateStatus
     *
     * @param array $data [explicite description]
     * @param int $isWaiter [explicite description]
     *
     * @return void
     */
    public function updateStatus(array $data, int $isWaiter = 0)
    {
        $user   = auth()->user();
        if($isWaiter == 1) {
            $user->restaurant_waiter()->update($data);
        } else {
            $user->restaurant_kitchen()->update($data);
        }
        $user->refresh();
        return $user;
    }

    /**
     * Method callWaiterNotify
     *
     * @return mixed
     */
    public function callWaiterNotify()
    {
        $user           = auth()->user();
        $devices        = $user->devices()->pluck('fcm_token')->toArray();
        $res_waiters    = RestaurantWaiter::where('restaurant_id',$user->restaurant_kitchen->restaurant_id)->get();

        foreach($res_waiters as $res_waiter)
        {
            $title              = "Your order Ready";
            $message            = "Your Order is Ready";
            $orderid            = $res_waiter->user_id;
            $send_notification  = sendNotification($title,$message,$devices,$orderid);
        }
        return $user;
    }


    /**
     * Method getCartdataWaiter
     *
     * @return Order
     */
    public function getCartdataWaiter(array $data): ?Order
    {
        $order        = isset($data['order_id']) ? Order::findOrFail($data['order_id']) : null;

        $order->loadMissing(
            [
                'order_items',
                'restaurant_table',
                'restaurant',
            ]
        );

        return $order->refresh();
    }


    /**
     * Method getwaiterOrderdata
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    function getwaiterOrderdata(array $data) : array
    {
        $page   = isset($data['page']) ? $data['page'] : 1;
        $limit  = isset($data['limit']) ? $data['limit'] : 10;
        $text   = isset($data['text']) ? $data['text'] : null;

        $user   = auth()->user();

        $user->loadMissing([
            'waiter_order'
        ]);

        $query = $user
        ->waiter_order()
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
     * Method placeOrderwaiter
     *
     * @param array $data [explicite description]
     *
     * @return App\Models\Order
     */
    function placeOrderwaiter(array $data): Order
    {
        // $card_id            = $data['card_id'] ?? null;
        $credit_amount      = $data['credit_amount'] ? $data['credit_amount'] : null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        // $pickup_point_id    = $data['pickup_point_id'] ? PickupPoint::findOrFail($data['pickup_point_id']) : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $devices            = $user->devices()->pluck('fcm_token')->toArray();

        $updateArr         = [];
        $paymentArr        = [];
        $stripe_customer_id = $user->stripe_customer_id;
        // dd($stripe_customer_id);

        if(isset($order->id))
        {
            if($order->total == $credit_amount)
            {
                $updateArr = [
                    'type'                  => Order::ORDER,
                    // 'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    // 'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
                ];
            }


            if( $order->total != $credit_amount )
            {
                $stripe         = new Stripe();
                $getCusCardId   = $stripe->fetchCustomer($stripe_customer_id);
                $defaultCardId  = $getCusCardId->default_source;

                $paymentArr = [
                    'amount'        => $amount * 100,
                    'currency'      => $order->restaurant->currency->code,
                    'customer'      => $user->stripe_customer_id,
                    // 'capture'       => false,
                    'source'        => $defaultCardId,
                    'description'   => "Order #{$order->id} place Successfully with Payment of {$amount}"
                ];
                $payment_data   = $stripe->createCharge($paymentArr);

                $updateArr = [
                    'type'                  => Order::ORDER,
                    'card_id'               => $defaultCardId,
                    'charge_id'             => $payment_data->id,
                    // 'pickup_point_id'       => ($pickup_point_id) ? $pickup_point_id->id : null,
                    // 'pickup_point_user_id'  => ($pickup_point_id) ? $pickup_point_id->user_id : null,
                    'credit_amount'         => $credit_amount,
                    'restaurant_table_id'   => ($table_id) ? $table_id : null,
                ];
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);

        $title              = "Preparing Your order";
        $message            = "Your Order is #".$order->id." placed";
        $orderid            = $order->id;
        $send_notification  = sendNotification($title,$message,$devices,$orderid);

        $bartitle           = "Order is placed by Customer";
        $barmessage         = "Order is #".$order->id." placed by customer";
        $bardevices         = $order->user->devices()->pluck('fcm_token')->toArray();
        $bar_notification   = sendNotification($bartitle,$barmessage,$bardevices,$orderid);

        return $order;
    }

    /**
     * Method takePayment
     *
     * @param array $data [explicite description]
     *
     * @return App\Models\Order
     */
    public function takePayment(array $data): Order
    {
        $order              = Order::findOrFail($data['order_id']);
        $card_id            = $data['card_id'] ?? null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $paymentArr        = [];
        if(isset($order->id))
        {
            if($order->total == $amount)
            {
                $paymentArr = [
                    'amount'        =>  $amount * 100,
                    'currency'      =>  $order->restaurant->currency->code,
                    'customer'      =>  $user->stripe_customer_id,
                    'source'        =>  $card_id,
                    'description'   =>  $order->id
                ];

                $stripe = new Stripe();
                $payment_data = $stripe->createCharge($paymentArr);

                $updateArr = [
                    'type'                  => Order::ORDER,
                    'card_id'               => $card_id,
                    'charge_id'             => $payment_data->id,
                    // 'credit_amount'         => $credit_amount,
                    'user_payment_method_id'=> UserPaymentMethod::CREDITCARD,
                ];
            }
            $order->refresh();
        }
        return $order;
    }

    /**
     * Method addNewCard
     *
     * @param array $data [explicite description]
     *
     * @return App\Models\Order
     * @throws \App\Exceptions\GeneralException
     */
    public function addNewCard(array $data): Order
    {
        $user = User::findOrFail($data['user_id']);
        $token          = isset( $data['token'] ) ? $this->userRepository->retrieveToken($data['token']) : null;
        $fingerprint    = $token->card->fingerprint;
        $cards          = $this->userRepository->fetchCard(['customer_id' => $user->stripe_customer_id]);
        $stripe         = new Stripe();

        // check card exist
        if( !$this->userRepository->checkCardAlreadyExist($cards, $fingerprint) )
        {
            return $source = $this->userRepository->attachSource($stripe, $user->stripe_customer_id, $token);
        }
        else
        {
            throw new GeneralException('Card is already taken for this customer.');
        }

        return $cards;
    }

    public function tableOrderLists(): Collection
    {
        $user   = auth()->user();
        $restaurant_id = $user->restaurant_waiter->restaurant_id;
        $orders = Order::where(['restaurant_id' => $restaurant_id, 'waiter_id' => $user->id])->where('type',Order::CART)->get();
        return $orders;
    }

    /**
     * Method ReOrder
     *
     * @param array $data [explicite description]
     *
     * @return \App\Models\Order
     */
    public function ReOrder(array $data): Order
    {
        $user                   = auth()->user();
        $reOrder                = Order::findOrFail($data['order_id']);
        $reOrderItems           = $reOrder->order_items;
        $newOrder               = $reOrder->replicate();
        $newOrder->type         = Order::CART;
        $newOrder->status       = Order::PENDNIG;
        $newOrder->save();

        foreach ($reOrderItems as  $item) {
            $item->offsetUnset('order_id');
            $newOrder->items()->create($item->toArray());
        }

        $newOrder->loadMissing(
            [
                'order_items',
                'restaurant_table',
                'restaurant'
            ]
        );
        return $newOrder;
    }

    public function customerTable(array $data)
    {
        $user = auth()->user();
        $customerTbl = CustomerTable::create($data);
        return $customerTbl;
    }
}