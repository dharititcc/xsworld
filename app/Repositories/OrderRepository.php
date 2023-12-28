<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\Category;
use App\Models\CreditPointsHistory;
use App\Models\CustomerTable;
use App\Models\FriendRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantPickupPoint;
use App\Models\RestaurantWaiter;
use App\Models\User;
use App\Models\UserPaymentMethod;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\CreditPoint;
use App\Repositories\Traits\OrderFlow;
use Barryvdh\DomPDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations\Items;
use Stripe\Source;
use Stripe\Token;

/**
 * Class OrderRepository.
*/
class OrderRepository extends BaseRepository
{
    use CreditPoint, OrderFlow;

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
                'latest_cart.order_items.addons',
                'latest_cart.order_items.mixer',
                'latest_cart.restaurant',
                'latest_cart.restaurant.restaurant_pickup_points' => function($query)
                {
                    return $query->status(RestaurantPickupPoint::ONLINE);
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

        $user->loadMissing(
            [
                'latest_order',
                'orders',
            ]
        );
        return $user
        ->orders()
        ->whereIn('status', [Order::ACCEPTED, Order::DELAY_ORDER, Order::PENDNIG, Order::COMPLETED])
        ->with([
            'reviews',
            'order_items',
            'order_items.mixer',
            'order_items.addons',
            'order_items.variation',
            'order_items.restaurant_item',
            'order_items.restaurant_item.restaurant',
            'order_items.restaurant_item.restaurant.currency',
            'order_items.restaurant_item.restaurant.country',
            'user',
            'restaurant_pickup_point',
            'restaurant_pickup_point.attachment',
            'pickup_point_user',
            'restaurant',
            'restaurant.restaurant_pickup_points',
            'restaurant.restaurant_pickup_points.attachment'
        ])->orderBy('id', 'desc')->first();
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
     * Method nextMemberShipValue
     *
     * @param $membership $membership [explicite description]
     *
     * @return array
     */
    public function nextMemberShipValue(array $membership): array
    {
        // dd($membership);
        if($membership['membership'] == config('xs.silver_membership') && $membership['membership_level'] == config('xs.silver_level'))
        {
            $nextMembership         = config('xs.gold_membership');
            $nextMembership_level   = config('xs.gold_level');
            $nextMembership_value   = config('xs.gold');

        } else if($membership['membership'] == config('xs.bronze_membership') && $membership['membership_level'] == config('xs.bronze_level')) {

            $nextMembership         = config('xs.silver_membership');
            $nextMembership_level   = config('xs.silver_level');
            $nextMembership_value   = config('xs.silver');

        } else if($membership['membership'] == config('xs.gold_membership') && $membership['membership_level'] == config('xs.gold_level')) {

            $nextMembership         = config('xs.platinum_membership');
            $nextMembership_level   = config('xs.platinum_level');
            $nextMembership_value   = config('xs.platinum');

        } else {

            $nextMembership         = config('xs.platinum_membership');
            $nextMembership_level   = config('xs.platinum_level');
            $nextMembership_value   = config('xs.platinum');

        }

        return $membership = [
            'nextMembership'        => $nextMembership,
            'nextMembership_level'  => $nextMembership_level,
            'nextMembership_value'  => $nextMembership_value,
        ];
    }

    /**
     * Method getRankBenifit
     *
     * @return array
     */
    function getRankBenifit(): array
    {
        $user   = auth()->user();
        $membership = $this->getMembership($user);

        $nextMembership = $this->nextMemberShipValue($membership);
        $points         = $this->getMembershipPoints($user);

        if( $nextMembership['nextMembership_level'] === config('xs.platinum_level') )
        {
            $membershipDifference = config('xs.gold')[1] - (config('xs.gold')[0] - 1);

            if( $points['current_points'] > config('xs.gold')[1] )
            {
                $currentMembershipDiff= $points['current_points'] - config('xs.gold')[1];
            }
            else
            {
                $currentMembershipDiff= config('xs.gold')[1] - $points['current_points'];
            }

            $actualPoints         = $membershipDifference - $currentMembershipDiff;

            $nextMembershipValue = ($actualPoints * 100) / $membershipDifference;
        }
        else
        {
            $membershipEnd        = $nextMembership['nextMembership_value'][0] - 1;
            $membershipDifference = $nextMembership['nextMembership_value'][1] - $membershipEnd;

            if( $points['current_points'] > $membershipDifference )
            {
                $currentMembershipDiff= $points['current_points'] - $membershipDifference;
            }
            else
            {
                $currentMembershipDiff= $membershipDifference - $points['current_points'];
                $currentMembershipDiff= $membershipDifference - $currentMembershipDiff;
            }

            // $actualPoints         = $membershipDifference - $currentMembershipDiff;

            $nextMembershipValue = ($currentMembershipDiff * 100) / $membershipDifference;
        }

        if($membership['membership'] == config('xs.platinum_membership'))
        {
            $nextMembershipValue = 100;
        }

        $data   = [
            'current_membership'            => $membership['membership'],
            'current_membership_level'      => $membership['membership_level'],
            'next_membership'               => $nextMembership['nextMembership'],
            'next_membership_level'         => $nextMembership['nextMembership_level'],
            'next_membership_percentage'    => (string) round($nextMembershipValue, 0),
            'referrals_points'              => $user->referrals->count() * 60,
            'rank_benifit_text'             => 'Complementary beverage on your birthday. Discounted options on certain & selected drinks , daily specials. '
        ];
        return $data;
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

        $creditAmount = $user->credit_amount ? number_format($user->credit_amount, 2, '.', '') : 0.00;

        $membership = $this->getMembership($user);

        $cart       = [
            'cart_count'        => isset($user->latest_cart->order_items) ? $user->latest_cart->order_items->sum('quantity') : 0,
            'restaurant_id'     => $user->latest_cart->restaurant->id ?? 0,
            'order_id'          => $user->latest_cart->id ?? 0,
            'credit_amount'     => (float) $creditAmount,
            'points'            => (int) $user->points,
            // 'membership'        => $membership,
            'membership'            => $membership['membership'],
            'membership_level'      => $membership['membership_level'],
        ];

        return $cart;
    }

    /**
     * Method getMembership
     *
     * @param User $user [explicite description]
     *
     * @return array
     */
    public function getMembership(User $user):array
    {
        $points = $this->getMembershipPoints($user);

        if ($points['current_points'] > $points['previous_points']) {
            // current quarter membership
            $membership = $this->getMembershipType($points['current_points']);
        } else {
            // previous quarter membership
            $membership = $this->getMembershipType($points['previous_points']);
        }

        return $membership;
    }

    /**
     * Method getMembershipPoints
     *
     * @param User $user [explicite description]
     *
     * @return array
     */
    public function getMembershipPoints(User $user): array
    {
        // quarter logic goes here
        $previousQuarter    = get_previous_quarter();
        $currentQuarter     = get_current_quarter();
        // get previous quarter points
        $previousQuarterOrders = $user->credit_points()->where(function ($query) use ($previousQuarter) {
            $query->whereRaw(DB::raw("DATE(created_at) BETWEEN '{$previousQuarter['start_date']}' AND '{$previousQuarter['end_date']}'"));
        })
        ->get();
        // echo common()->formatSql($previousQuarterOrders);die;
        // get current quarter points
        $currentQuarterOrders = $user->credit_points()->where(function ($query) use ($currentQuarter) {
            $query->whereRaw(DB::raw("DATE(created_at) BETWEEN '{$currentQuarter['start_date']}' AND '{$currentQuarter['end_date']}'"));
        })
        ->get();
        // echo common()->formatSql($currentQuarterOrders);die;
        $previousQuarterPoints  = $previousQuarterOrders->sum('points');
        $currentQuarterPoints   = $currentQuarterOrders->sum('points');

        if($currentQuarterPoints == 0)
        {
            $currentQuarterPoints = $user->points;
        }

        return [
            'current_points' => round($currentQuarterPoints),
            'previous_points'=> round($previousQuarterPoints)
        ];
    }

    /**
     * Method getMembershipType
     *
     * @param float $points [explicite description]
     *
     * @return array
     */
    public function getMembershipType(float $points): array
    {
        $membership         = config('xs.bronze_membership');
        $membership_level   = config('xs.bronze_level');
                        //      >0                                   <=100
        if ($points > config('xs.bronze')[0] && $points <= config('xs.bronze')[1]) {
            $membership         = config('xs.bronze_membership');
            $membership_level   = config('xs.bronze_level');
            //                  >100                                 <=200
        } else if ($points > config('xs.bronze')[1] && $points <= config('xs.silver')[1]) {
            $membership         = config('xs.silver_membership');
            $membership_level   = config('xs.silver_level');
            //                  >200                                 <=300
        } else if ($points > config('xs.silver')[1] && $points <= config('xs.gold')[1]) {
            $membership         = config('xs.gold_membership');
            $membership_level   = config('xs.gold_level');
            //                  >300
        } else if ($points > config('xs.gold')[1]) {
            $membership         = config('xs.platinum_membership');
            $membership_level   = config('xs.platinum_level');
        } else {
            $membership         = config('xs.bronze_membership');
            $membership_level   = config('xs.bronze_level');
        }

        return $membership = [
            'membership'        => $membership,
            'membership_level'  => $membership_level,
        ];

        // return $membership;
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

        // dd($this->checkOrderCategoryType($order->order_items));
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

    /**
     * Method deleteCart
     *
     * @param array $data [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function deleteCart(array $data): bool
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
        $userCreditAmountBalance = $user->credit_amount;
        $refundCreditAmount = $order->credit_amount;

        if(isset($order->id))
        {
            if($status == Order::CUSTOMER_CANCELED)
            {
                if($order->charge_id)
                {
                    $stripe            = new Stripe();
                    $refundArr = [
                        'charge'       => $order->charge_id,
                    ];
                    $refund_data                = $stripe->refundCreate($refundArr);
                    $updateArr['refunded_id']   = $refund_data->id;
                }
                $updateArr['cancel_date']   = Carbon::now();
                $updateArr['status']        = $status;
            }

            $order->update($updateArr);
            $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
            // update user's credit amount
            $this->updateUserPoints($user, ['credit_amount' => $totalCreditAmount]);

            $bartitle           = "Order canceled";
            $barmessage         = "Order #".$order->id." is canceled by customer";
            $bardevices         = $order->pickup_point_user_id ? $order->pickup_point_user->devices()->pluck('fcm_token')->toArray() : [];
            if(!empty( $bardevices )) {
                $bar_notification   = sendNotification($bartitle,$barmessage,$bardevices,$order->id);
            }

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
        $category_id = $this->categoryGet();
        $orders = Order::whereIn('restaurant_id',$data);
        if($is_history === 0) {
            $orderTbl = $orders
            // ->with(['order_items' => function($query) use($category_id){
            //     $query->where('category_id',$category_id);
            // },])
            ->where('type',Order::ORDER)
            ->whereIn('status',[Order::PENDNIG,Order::ACCEPTED,Order::WAITER_PENDING,Order::CURRENTLY_BEING_PREPARED])
            ->whereNotIn('order_category_type', [0])->orderByDesc('id')->get();
        } else {
            $orderTbl = $orders->whereIn('status',[Order::COMPLETED,Order::FULL_REFUND, Order::PARTIAL_REFUND, Order::RESTAURANT_CANCELED, Order::CUSTOMER_CANCELED, Order::KITCHEN_CONFIRM])->where('type',Order::ORDER)->whereNotIn('order_category_type', [0])->orderByDesc('id')->get();
        }
        if($orderTbl)
        {
            return (object)$orderTbl;
        } else {
            throw new GeneralException('Order is not found.');
        }
    }

    /**
     * Method getKitchenCollections
     *
     * @param array $data [explicite description]
     *
     * @return Collection
     */
    public function getKitchenCollections(array $data) : Collection
    {
        $category_id = $this->categoryGet();
        $orders = Order::whereIn('restaurant_id',$data)
        // ->with(['order_items' => function($query) use($category_id) { $query->where('category_id', $category_id); }])
        ->where('type', Order::ORDER)
        ->where('status', [Order::READYFORPICKUP])
        // ->whereHas('order_items', function($query) use($category_id)
        // {
        //     $query->where('category_id',$category_id)
        //         ->where('status', OrderItem::COMPLETED);
        // })
        ->whereNotIn('order_category_type', [0])
        ->orderBy('id','asc')
        ->get();

        return $orders;
    }

    /**
     * Method updateStatus
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function categoryGet()
    {
        $auth_kitchen = auth('api')->user();
        foreach($auth_kitchen->restaurant_kitchen->restaurant->main_categories as $category)
        {
            if($category->name == "Food") {
                $category_id = $category->id;
            }
        }
        return $category_id;
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
        $res_waiters    = RestaurantWaiter::where('restaurant_id',$user->restaurant_kitchen->restaurant_id)->get();

        foreach($res_waiters as $res_waiter)
        {
            $devices            = $res_waiter->user->devices()->pluck('fcm_token')->toArray();
            $title              = "Kitchen calling you";
            $message            = "Kitchen calling you";
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
        // $pickup_point_id    = $data['pickup_point_id'] ? RestaurantPickupPoint::findOrFail($data['pickup_point_id']) : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $devices            = $user->devices()->pluck('fcm_token')->toArray();

        $kitchens          = $order->restaurant->kitchens;
        $kitchen_token     = [];

        foreach ($kitchens as $kitchen) {
            $token   = $kitchen->user->devices()->pluck('fcm_token');
            if(isset($token[0]))
            {
                $kitchen_token[]    = $token[0];
            }
        }

        $updateArr         = [];
        $paymentArr        = [];
        $stripe_customer_id = $user->stripe_customer_id;

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
                    'status'                => Order::CURRENTLY_BEING_PREPARED,
                ];
            }


            if( $order->total != $credit_amount )
            {
                $stripe         = new Stripe();
                $customer_cards = $stripe->fetchCards($stripe_customer_id)->toArray();
                if(empty($customer_cards['data'])) {
                    throw new GeneralException('Please ask customer to Add Card details');
                }
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
                    'status'                => Order::CURRENTLY_BEING_PREPARED,
                ];
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);
        // 

        //customer notify
        $title              = "place new order";
        $message            = "Your Order has been #".$order->id." placed";
        $orderid            = $order->id;
        if(!empty($devices)) {
            $send_notification  = sendNotification($title,$message,$devices,$orderid);
        }
        

        //kitchen notify
        $kitchentitle           = "place new order";
        $kitchenmessage         = "New Order has been #".$order->id." placed";
        // $kitchendevices         = $order->user->devices()->pluck('fcm_token')->toArray();
        $kitchen_notification   = sendNotification($kitchentitle,$kitchenmessage,$kitchen_token,$orderid);

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
     * Method reOrder
     *
     * @param array $data [explicite description]
     *
     * @return \App\Models\Order
     */
    public function reOrder(array $data): Order
    {
        $user                   = auth()->user();
        $orderAgain             = $user->orders()->where('restaurant_id', $data['restaurant_id'])->where('type',Order::ORDER)->whereNotIn('status',[Order::CUSTOMER_CANCELED,Order::RESTAURANT_CANCELED,Order::RESTAURANT_TOXICATION])->orderByDesc('id')->first();

        $resName = Restaurant::select('name')->where('id',$data['restaurant_id'])->first();
        if( !isset($orderAgain->id) )
        {
            throw new GeneralException('You have not placed order yet from ' . $resName['name']);
        }

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant']);

        $latestCart = $user->latest_cart;

        if( isset( $latestCart->id ) )
        {
            // check restaurant id available in the cart
            $latestCart->delete();
        }

        $reOrder                        = $orderAgain;
        $reOrderItems                   = $reOrder->order_items;
        $newOrder                       = $reOrder->replicate();
        $newOrder->type                 = Order::CART;
        $newOrder->status               = Order::PENDNIG;
        $newOrder->credit_amount        = 0.00;
        $newOrder->transaction_id       = null;
        $newOrder->card_id              = null;
        $newOrder->charge_id            = null;
        $newOrder->apply_time           = null;
        $newOrder->last_delayed_time    = null;
        $newOrder->remaining_date       = null;
        $newOrder->accepted_date        = null;
        $newOrder->pickup_point_id      = null;
        $newOrder->pickup_point_user_id = null;
        $newOrder->waiter_id            = null;
        $newOrder->served_date          = null;
        $newOrder->completion_date      = null;
        $newOrder->restaurant_table_id  = null;
        $newOrder->created_at           = Carbon::now();
        $newOrder->updated_at           = Carbon::now();

        $newOrder->save();

        $reOrderItems->loadMissing(['addons','mixer']);

        // get order items and store into order items table
        foreach ($reOrderItems as  $item) {
            // $item->offsetUnset('order_id');
            $item->status = OrderItem::PENDNIG;
            $newOrderItem = $newOrder->order_items()->create($item->toArray());

            // create addons
            if($item->addons->count()) {
                foreach( $item->addons as $addon )
                {
                    // clear old parent item id
                    $addon->offsetUnset('parent_item_id');
                    $addon->offsetUnset('order_id');
                    $addon->order_id =  $newOrderItem->order_id;
                    $newOrderItem->addons()->create($addon->toArray());
                }
            }

            // create mixer
            if( isset( $item->mixer->id ) )
            {
                // create mixer for specific item
                // clear old parent item id
                $item->mixer->offsetUnset('parent_item_id');
                $item->mixer->offsetUnset('order_id');
                $item->mixer->order_id =  $newOrderItem->order_id;
                $newOrderItem->mixer()->create($item->mixer->toArray());
            }
        }

        $newOrder->refresh();

        $newOrder->loadMissing(
            [
                'order_items',
                'order_items.addons',
                'order_items.mixer',
                'restaurant_table',
                'restaurant'
            ]
        );
        return $newOrder;
    }

    public function customerTable(array $data)
    {
        $getcusTbl = CustomerTable::where('user_id' , $data['user_id'])->where('restaurant_table_id',$data['restaurant_table_id'])->first();
        if($getcusTbl) {
            throw new GeneralException('Already table allocated to this Customer');
            $customerTbl = 0;
        } else {
            $customerTbl = CustomerTable::updateOrCreate([
                'restaurant_table_id' => $data['restaurant_table_id'],
                'user_id'       => $data['user_id'],
            ],[
                'waiter_id'     => $data['waiter_id']
            ]);
            // $customerTbl = CustomerTable::create($data);
        }

        Order::where('type',Order::ORDER)->where('status', Order::PENDNIG)->where('restaurant_table_id',$data['restaurant_table_id'])->where('user_id', $data['user_id'])->update(['waiter_id' => $data['waiter_id']]);

        return $customerTbl;
    }

    public function customerTableDel(array $data)
    {
        if($data['order_id'])
        {
            // Order::where('id',$data['order_id'])->update(['status' => Order::COMPLETED]);
            $order = Order::findOrFail($data['order_id']);
            if($order->type == Order::CART)
            {
                $order->delete();
            }
    
            if($order->id){
                $points                     = $order->total * 3;
                $update['points']           = $order->user->points + round($points);
                $order->user->update($update);
                $creditArr['user_id']       = $order->user->id;
                $creditArr['order_id']      = $order->id;
                $creditArr['credit_point']  = $order->total;
                $creditArr['total']         = $update['points'];
                CreditPointsHistory::create($creditArr);
            }
        }
        $customerTblDel = CustomerTable::where('user_id' , $data['user_id'])->where('restaurant_table_id',$data['restaurant_table_id'])->delete();
        return $customerTblDel;
    }

    public function venueUserList(array $data)
    {
        $now        = Carbon::now(); //$now->format('Y-m-d H:i:s')
        $lastHour   = Carbon::now()->subHour(1);
        // dd($now);
        // dd($lastHour);
        // dd($data['restaurant_id']);
        $venueList  = Order::where('restaurant_id',$data['restaurant_id'])
                    ->whereNotIn('status', [Order::CUSTOMER_CANCELED])
                    ->where('type',Order::ORDER)
                    // ->where('updated_at',)
                    // ->where('updated_at', function($query) use($lastHour)
                    // {
                    //     return $query->where('updated_at', '<', $lastHour);
                    // })
                    ->distinct('orders.user_id')
                    ->get();
        return $venueList;
        // dd($venueList);
    }


    public function sendFriendReq(array $data)
    {
        $auth_user = auth()->user();
        $FriendRequest = FriendRequest::create([
            'user_id'   => $auth_user->id,
            'friend_id' => $data['user_id'],
        ]);

        $FriendRequest = FriendRequest::create([
            'user_id'   => $data['user_id'],
            'friend_id' => $auth_user->id,
        ]);
        return $FriendRequest;
    }

    public function friendRequestStatus(array $data)
    {
        $auth_user = auth()->user();
        // FriendRequest::where('user_id', $data['user_id'])->where('friend_id', $data['user_id'])
        //SELECT * FROM `friend_requests` where ( user_id = 3 OR friend_id = 3) AND ( user_id = 18 OR friend_id = 18 );
        $FriendRequest = FriendRequest::create([
            'user_id'   => $data['user_id'],
            'friend_id' => $auth_user->id,

        ]);
    }

    /**
     * Method printOrder
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function printOrder($data)
    {
        $order  = Order::where(['id' => $data])->first();

        // Generate PDF
        $pdf        = app('dompdf.wrapper');
        $pdf->loadView('pdf.index',compact('order'));
        $filename   = 'invoice_'.$order->id.'.pdf';
        $content    = $pdf->output();
        $file       = public_path('storage/order_pdf');
        if(!is_dir($file)) {
            mkdir($file, 0755, true);
        }
        //Upload PDF to storage folder
        file_put_contents('storage/order_pdf/'.$filename, $content);
        $destinationPath = asset('storage/order_pdf/').'/'.$filename;
        return $destinationPath;
    }
}