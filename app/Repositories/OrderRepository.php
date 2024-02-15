<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\Traits\OrderStatus;
use App\Models\Category;
use App\Models\CreditPointsHistory;
use App\Models\CustomerTable;
use App\Models\FriendRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use App\Models\OrderSplit;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantPickupPoint;
use App\Models\RestaurantTable;
use App\Models\RestaurantWaiter;
use App\Models\User;
use App\Models\UserPaymentMethod;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\CreditPoint;
use App\Repositories\Traits\OrderFlow;
use App\Repositories\Traits\XSNotifications;
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
use Illuminate\Support\Facades\File;

/**
 * Class OrderRepository.
*/
class OrderRepository extends BaseRepository
{
    use CreditPoint, OrderFlow, OrderStatus, XSNotifications;

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
        $user       = auth()->user();
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
        $previousQuarterOrders = $user->credit_points()->where('type', 1)->where(function ($query) use ($previousQuarter) {
            $query->whereRaw(DB::raw("DATE(created_at) BETWEEN '{$previousQuarter['start_date']}' AND '{$previousQuarter['end_date']}'"));
        })
        ->get();
        // echo common()->formatSql($previousQuarterOrders);die;
        // get current quarter points
        $currentQuarterOrders = $user->credit_points()->where('type', 1)->where(function ($query) use ($currentQuarter) {
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
        $order      = Order::findOrFail($data['order_id']);

        if(isset($order->id))
        {
            // check if order has customer table
            if( isset( $order->customer_table->id ) )
            {
                // update customer table to awaiting service
                $order->customer_table()->update(['order_id' => null]);
            }

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
                $updateArr['status']        = Order::CUSTOMER_CANCELED;
                $updateArr['waiter_status'] = Order::CUSTOMER_CANCELED;

                $order->update($updateArr);
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $totalCreditAmount]);

                // deallocate table
                // update customer table update
                CustomerTable::where('user_id', $order->user->id)->where('order_id', $order->id)->delete();

                if( isset( $order->restaurant_table_id ) )
                {
                    // send notification to kitchen if order is for food
                    if( isset($order->order_split_food->id) )
                    {
                        $kitchenTitle    = 'Order cancelled';
                        $kitchenMessage  = "Order #".$order->id." is cancelled by customer";
                        $this->notifyKitchens($order, $kitchenTitle, $kitchenMessage);
                    }

                    // send notification to waiters
                    $kitchenTitle    = 'Order cancelled';
                    $kitchenMessage  = "Order #".$order->id." is cancelled by customer";
                    $this->notifyWaiters($order, $kitchenTitle, $kitchenMessage, Order::WAITER_CANCEL_ORDER);
                }
                else
                {
                    $bartitle           = "Order cancelled";
                    $barmessage         = "Order #".$order->id." is cancelled by customer";
                    $this->notifyBars($order, $bartitle, $barmessage);
                }
            }
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

        $query = Order::query()
        ->with([
            'restaurant',
            'user',
            'reviews',
            'order_items',
            'order_mixer',
        ])
        ->where('type', Order::ORDER)
        ->whereIn('waiter_status', [Order::COMPLETED, Order::CUSTOMER_CANCELED])
        ->whereNotNull('restaurant_table_id');

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
     * @return bool
     */
    function placeOrderwaiter(array $data): bool
    {
        $orderIdArr         = [];
        $credit_amount      = $data['credit_amount'] ? $data['credit_amount'] : null;
        $amount             = $data['amount'] ? $data['amount'] : null;
        $table_id           = $data['table_id'] ? $data['table_id'] : null;
        $order              = Order::findOrFail($data['order_id']);
        $user               = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $stripe_customer_id = $user->stripe_customer_id;
        $stripe             = new Stripe();
        $getCusCardId       = $stripe->fetchCustomer($stripe_customer_id);
        $defaultCardId      = $getCusCardId->default_source;
        $pickup_point_id    = $this->randomPickpickPoint($order);
        $isTableActive      = RestaurantTable::withTrashed()->where('id', $table_id)->first();

        if(!isset( $defaultCardId ))
        {
            // send notification to customer for card
            $this->notifyCustomer($order, 'Please add credit card', 'You need to add credit card', User::NOTIFICATION_CARD);
            throw new GeneralException('Please retry after some time as we have notify customer for the payment details');
        }

        // load missing order items
        $order->loadMissing([
            'order_splits',
            'order_split_food',
            'order_split_drink',
            'restaurant',
            'restaurant.kitchens'
        ]);

        // check if qr is enable or not
        if( isset($isTableActive->id) )
        {
            if( $isTableActive->deleted_at )
            {
                throw new GeneralException('You cannot place order as QR is deleted.');
            }

            if( $isTableActive->status == 0 )
            {
                throw new GeneralException('You cannot place order as QR is disabled.');
            }
        }

        if( isset($order->order_split_food->id) )
        {
            if( $order->restaurant->kitchens->count() )
            {
                $openKitchens = $order->restaurant->kitchens()->where('status', 1)->get();

                if( $openKitchens->count() === 0 )
                {
                    throw new GeneralException('You cannot able to place order as kitchen is closed.');
                }
            }
            else
            {
                throw new GeneralException('You cannot able to place order as there is no kitchen found.');
            }
        }

        // check if order if of category type both or single(food/drink)
        if( $order->order_category_type == Order::BOTH )
        {
            // check order split count > 1
            if( $order->order_splits->count() > 1 )
            {
                foreach( $order->order_splits as $key => $split )
                {
                    $latest = null;
                    $split->loadMissing(['items']);

                    if( $key === 0 )
                    {
                        $orderArr = [
                            'user_id'               => $user->id,
                            'order_category_type'   => $split->is_food == 1 ? Order::FOOD : Order::DRINK,
                            'restaurant_id'         => $order->restaurant_id,
                            'pickup_point_id'       => $split->is_food == 0 ? $pickup_point_id->id : null,
                            'pickup_point_user_id'  => $split->is_food == 0 ? $pickup_point_id->user_id : null,
                            'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                            'status'                => Order::PENDNIG,
                            'waiter_status'         => Order::CURRENTLY_BEING_PREPARED,
                            'currency_id'           => $order->restaurant->currency_id,
                            'place_at'              => Carbon::now(),
                            'type'                  => Order::ORDER
                        ];

                        $order->update($orderArr);

                        $split->update(['order_id' => $order->id]);
                        $split->items()->update(['order_id' => $order->id]);

                        $order->refresh();

                        // update total of the order by items
                        $order->loadMissing(['items']);
                        $order->update(['total' => $split->all_items->sum('total')]);

                        $latest = Order::with([
                            'restaurant',
                            'restaurant.kitchens',
                            'order_splits',
                            'order_split_food',
                            'order_split_drink'
                        ])->find($order->id);
                    }
                    else
                    {
                        $orderArr = [
                            'user_id'               => $user->id,
                            'order_category_type'   => $split->is_food == 1 ? Order::FOOD : Order::DRINK,
                            'restaurant_id'         => $order->restaurant_id,
                            'pickup_point_id'       => $split->is_food == 0 ? $pickup_point_id->id : null,
                            'pickup_point_user_id'  => $split->is_food == 0 ? $pickup_point_id->user_id : null,
                            'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                            'type'                  => Order::ORDER,
                            'status'                => Order::PENDNIG,
                            'waiter_status'         => Order::CURRENTLY_BEING_PREPARED,
                            'currency_id'           => $order->restaurant->currency_id,
                            'place_at'              => Carbon::now(),
                        ];

                        $order = Order::create($orderArr);

                        $split->update(['order_id' => $order->id]);
                        $split->items()->update(['order_id' => $order->id]);

                        $order->refresh();

                        // update total of the order by items
                        $order->loadMissing(['items']);
                        $order->update(['total' => $split->all_items->sum('total')]);

                        $latest = Order::with([
                            'restaurant',
                            'restaurant.kitchens',
                            'order_splits',
                            'order_split_food',
                            'order_split_drink'
                        ])->find($order->id);
                    }

                    // charge payment
                    $this->getOrderPayment($latest, $user, $credit_amount, $latest->total, $defaultCardId);

                    $orderIdArr[] = $latest->id;

                    $getcusTbl = CustomerTable::where('user_id', $user->id)->where('restaurant_table_id', $table_id)->where('order_id', $latest->id)->first();
                    if($getcusTbl) {
                        // throw new GeneralException('Already table allocated');
                        // $customerTbl = 0;
                        $getcusTbl->update(['order_id' => $latest->id]);
                    } else {
                        $customerTbl = CustomerTable::updateOrCreate([
                            'restaurant_table_id'   => $table_id,
                            'user_id'               => $user->id,
                            'order_id'              => $latest->id,
                        ]);
                    }

                    // send notification to kitchens of the restaurant if order is food
                    if( isset($latest->order_split_food->id) )
                    {
                        $kitchenTitle    = 'New order placed by waiter';
                        $kitchenMessage  = "Order is #{$latest->id} placed by waiter";
                        $this->notifyKitchens($latest, $kitchenTitle, $kitchenMessage);
                    }

                    // customer notification
                    $text               = $latest->restaurant->name. ' is processing your order';
                    $title              = $text;
                    $message            = "Your Order is #".$latest->id." placed";

                    $this->notifyCustomer($latest, $title, $message);

                    // send notification to bar of the restaurant if order is drink
                    if( isset($latest->order_split_drink->id) )
                    {
                        $bartitle           = "Order is placed by waiter";
                        $barmessage         = "Order is #".$latest->id." placed by waiter";
                        $this->notifyBars($latest, $bartitle, $barmessage);
                    }
                }
            }
        }
        else
        {
            $updateArr          = [];

            $order->update(['pickup_point_id' => $pickup_point_id->id, 'pickup_point_user_id' => $pickup_point_id->user_id]);

            if(isset($order->id))
            {
                $updateArr = [
                    'user_id'               => $user->id,
                    'restaurant_id'         => $order->restaurant_id,
                    'pickup_point_id'       => isset($order->order_split_drink->id) ? $pickup_point_id->id : null,
                    'pickup_point_user_id'  => isset($order->order_split_drink->id) ? $pickup_point_id->user_id : null,
                    'restaurant_table_id'   => isset($table_id) ? $table_id : null,
                ];
                $order->update($updateArr);
                $this->getOrderPayment($order, $user, $credit_amount, $amount, $defaultCardId);
            }

            $order->refresh();
            $order->loadMissing(['items']);

            $orderIdArr[] = $order->id;

            $getcusTbl = CustomerTable::where('user_id', $user->id)->where('restaurant_table_id', $table_id)->first();
            if($getcusTbl) {
                // throw new GeneralException('Already table allocated');
                // $customerTbl = 0;
                $getcusTbl->update(['order_id' => $order->id]);
            } else {
                $customerTbl = CustomerTable::updateOrCreate([
                    'restaurant_table_id'   => $table_id,
                    'user_id'               => $user->id,
                    'order_id'              => $order->id,
                ]);
            }

            // send notification to kitchens of the restaurant if order is food
            if( isset($order->order_split_food->id) )
            {
                $kitchenTitle    = 'New order placed by waiter';
                $kitchenMessage  = "Order is #{$order->id} placed by waiter";
                $this->notifyKitchens($order, $kitchenTitle, $kitchenMessage);
            }

            // customer notification
            $text               = $order->restaurant->name. ' is processing your order';
            $title              = $text;
            $message            = "Your Order is #".$order->id." placed";

            $this->notifyCustomer($order, $title, $message);

            // send notification to bar of the restaurant if order is drink
            if( isset($order->order_split_drink->id) )
            {
                $bartitle           = "Order is placed by waiter";
                $barmessage         = "Order is #".$order->id." placed by waiter";
                $this->notifyBars($order, $bartitle, $barmessage);
            }
        }

        // generate pdf
        $this->generatePDF($orderIdArr);

        // take payment
        $this->captureKitchenCharge($orderIdArr);

        return true;
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
        $orderAgain             = $user->orders()->where('restaurant_id', $data['restaurant_id'])->where('type', Order::ORDER)->whereIn('status',[Order::CONFIRM_PICKUP])->orderByDesc('id')->first();

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
        $reOrderSplit                   = $reOrder->order_splits;
        $newOrder                       = $reOrder->replicate();
        $newOrder->type                 = Order::CART;
        $newOrder->status               = Order::PENDNIG;
        $newOrder->credit_amount        = 0.00;
        $newOrder->order_category_type  = $reOrder->order_category_type;
        $newOrder->transaction_id       = null;
        $newOrder->card_id              = null;
        $newOrder->charge_id            = null;
        $newOrder->apply_time           = null;
        $newOrder->last_delayed_time    = null;
        $newOrder->remaining_date       = null;
        $newOrder->accepted_date        = null;
        $newOrder->waiter_id            = null;
        $newOrder->served_date          = null;
        $newOrder->completion_date      = null;
        $newOrder->restaurant_table_id  = null;
        $newOrder->created_at           = Carbon::now();
        $newOrder->updated_at           = Carbon::now();

        $newOrder->save();

        $orderSplit = OrderSplit::create([
            'order_id'  => $newOrder->id,
            'is_food'   => $reOrderSplit[0]->is_food,
            'status'    => OrderSplit::PENDING
        ]);

        $reOrderItems->loadMissing(['addons','mixer']);

        // get order items and store into order items table
        foreach ($reOrderItems as  $item) {
            $item->offsetUnset('order_split_id');
            $item->status           = OrderItem::PENDNIG;
            $item->order_split_id   = $orderSplit->id;
            $newOrderItem = $newOrder->order_items()->create($item->toArray());

            // create addons
            if($item->addons->count()) {
                foreach( $item->addons as $addon )
                {
                    // clear old parent item id
                    $addon->offsetUnset('parent_item_id');
                    $addon->offsetUnset('order_id');
                    $addon->order_id        =  $newOrderItem->order_id;
                    $addon->order_split_id  =  $orderSplit->id;
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
                $item->mixer->offsetUnset('order_split_id');
                $item->mixer->order_id          =  $newOrderItem->order_id;
                $item->mixer->order_split_id    =  $orderSplit->id;
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
        $getcusTbl = CustomerTable::where('user_id' , $data['user_id'])->where('restaurant_table_id', $data['restaurant_table_id'])->first();
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
        }

        Order::where('type',Order::ORDER)->where('status', Order::PENDNIG)->where('restaurant_table_id',$data['restaurant_table_id'])->where('user_id', $data['user_id'])->update(['waiter_id' => $data['waiter_id']]);

        return $customerTbl;
    }

    public function customerTableDel(array $data)
    {
        if($data['order_id'])
        {
            $order = Order::findOrFail($data['order_id']);
            if($order->type == Order::CART)
            {
                $order->delete();
            }

            if($order->id)
            {
                // update order to completed
                $order->update(['waiter_status' => Order::COMPLETED, 'status' => Order::CONFIRM_PICKUP]);
            }
        }
        $customerTblDel = CustomerTable::where('user_id' , $data['user_id'])->where('restaurant_table_id', $data['restaurant_table_id'])->delete();
        return $customerTblDel;
    }

    /**
     * Method venueUserList
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function venueUserList(array $data)
    {
        $user           = auth()->user();
        $now            = Carbon::now();
        $lastHour       = Carbon::now()->subHour(1);
        $membershipLevel= isset( $data['membership_level'] ) ? $data['membership_level'] : "";

        // quarter logic goes here
        $previousQuarter    = get_previous_quarter();
        $currentQuarter     = get_current_quarter();

        $venueList  = User::query()
                    ->select([
                        // 'users.*',
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        'users.email',
                        'users.username',
                        'users.credit_amount',
                        'users.points',
                        DB::raw(
                            "
                            (
                                CASE
                                    WHEN friendship_status_tbl.friendship_status = 0 THEN 0
                                    WHEN friendship_status_tbl.friendship_status = 1 THEN 1
                                    WHEN friendship_status_tbl.friendship_status = 2 THEN 2
                                    ELSE
                                    3
                                END
                            ) AS friendship_status
                        "),
                        DB::raw("COALESCE(previous_qua, 0) AS previous_points"),
                        DB::raw("COALESCE(Abs(curr_qua), 0) AS current_points"),
                        DB::raw(
                        "
                            (
                                CASE
                                    WHEN COALESCE(previous_qua, 0) > COALESCE(curr_qua, 0) THEN CAST(COALESCE(previous_qua, 0) AS DECIMAL(20,2))
                                    ELSE
                                    CAST(COALESCE(curr_qua, 0) AS DECIMAL(20,2))
                                END
                            ) AS current_membership_points
                        ")
                    ])
                    ->with([
                        'attachment',
                        'friend',
                        'orders',
                        'credit_points',
                        'orders.restaurant'
                    ])
                    ->leftJoin('orders', 'orders.user_id', '=', 'users.id')
                    ->leftJoin(DB::raw(
                    "
                        (
                            SELECT
                                SUM(points) AS previous_qua,
                                user_id
                            FROM credit_points_histories
                            WHERE DATE(created_at) BETWEEN '{$previousQuarter['start_date']}' AND '{$previousQuarter['end_date']}'
                            AND type = 1
                            GROUP BY user_id
                        ) AS `previous_quarter`
                    "
                    ), function($join)
                    {
                        $join->on('users.id', '=', 'previous_quarter.user_id');
                    })
                    ->leftJoin(DB::raw(
                    "
                        (
                            SELECT
                                SUM(points) AS curr_qua,
                                user_id
                            FROM credit_points_histories
                            WHERE DATE(created_at) BETWEEN '{$currentQuarter['start_date']}' AND '{$currentQuarter['end_date']}'
                            AND type = 1
                            GROUP BY user_id
                        ) AS `current_quarter`
                    "
                    ), function($join)
                    {
                        $join->on('users.id', '=', 'current_quarter.user_id');
                    })
                    ->leftJoin(DB::raw
                    (
                        "(SELECT
                            status AS friendship_status,
                            user_id,
                            friend_id
                        FROM friendships
                        WHERE (
                            user_id = {$user->id}
                            OR friend_id = {$user->id}
                        ))  AS `friendship_status_tbl`
                        "
                    ), function($join)
                    {
                        $join->on('users.id', '=', 'friendship_status_tbl.user_id');
                        $join->orOn('users.id', '=', 'friendship_status_tbl.friend_id');
                    })
                    ->where('restaurant_id', $data['restaurant_id'])
                    ->whereNotIn('status', [Order::CUSTOMER_CANCELED])
                    ->where('users.id', '!=', $user->id)
                    ->where('type', Order::ORDER)
                    ->groupBy('orders.user_id')
                    ->get();
                    // echo common()->formatSql($venueList);die;
        if( $membershipLevel != "" )
        {
            $filtered = '';//$venueList;

            $membershipArr = explode(',', $membershipLevel);
                if( in_array(config('xs.bronze_level'), $membershipArr) )
            {
                $filtered =$venueList->whereBetween('current_membership_points', config('xs.bronze'));
            }
            if( in_array(config('xs.silver_level'), $membershipArr) )
            {
                if(!empty($filtered) && $filtered->count() != 0){
                    $filtered = $filtered->concat($venueList->whereBetween('current_membership_points', config('xs.silver')));
                }else{
                    $filtered = $venueList->whereBetween('current_membership_points', config('xs.silver'));
                }
            }
            if( in_array(config('xs.gold_level'), $membershipArr) )
            {
                if(!empty($filtered) && $filtered->count() != 0){
                    $filtered = $filtered->concat($venueList->whereBetween('current_membership_points', config('xs.gold')));
                }else{
                    $filtered = $venueList->whereBetween('current_membership_points', config('xs.gold'));
                }
            }
            if( in_array(config('xs.platinum_level'), $membershipArr) )
            {
                if(!empty($filtered) && $filtered->count() != 0)
                {
                    $filtered = $filtered->concat($venueList->where('current_membership_points','>',300));
                }else{
                    $filtered = $venueList->where('current_membership_points','>',300);
                }
            }
        }
        else
        {
            $filtered = $venueList;
        }

        return $filtered;
    }


    public function getMembershipQuery($restaurantID,$userId=0)
    {

        $user = auth()->user();

        return $user->friends;

    }

    public function sendFriendReq(array $data)
    {
        $auth_user = auth()->user();
        $friend = User::find($data['user_id']);
        $checkDeclineRequest = User::where('id', $data['user_id'])
            ->leftJoin(DB::raw
            (
                "(SELECT
                    id AS friendship_id,
                    status AS friendship_status,
                    user_id,
                    friend_id
                FROM friendships
                WHERE (
                    user_id = {$auth_user->id}
                    OR friend_id = {$auth_user->id}
                ))  AS `friendship_status_tbl`
                "
                ),function($join)
            {
                $join->on('users.id', '=', 'friendship_status_tbl.user_id');
                $join->orOn('users.id', '=', 'friendship_status_tbl.friend_id');
            })
            ->where('friendship_status_tbl.friendship_status', 2)
            ->first();

        if(!empty($checkDeclineRequest))
        {
            FriendRequest::where('id', $checkDeclineRequest->friendship_id)->delete();
        }
        

        $checkFriend    = FriendRequest::where('friend_id',$auth_user->id)->where('user_id',$data['user_id'])->first();
        if(isset($checkFriend)) {
            if($checkFriend->status != 2) {
                throw new GeneralException('Already send Friend request');
            }
        }
        $auth_user->friends()->attach($friend->id);

        // $FriendRequest = FriendRequest::create([
        //     'user_id'   => $auth_user->id,
        //     'friend_id' => $data['user_id'],
        // ]);

        // $FriendRequest = FriendRequest::create([
        //     'user_id'   => $data['user_id'],
        //     'friend_id' => $auth_user->id,
        // ]);
        $friends = $auth_user->friends;
        $title              = "You received new Friend Request";
        $message            = "You have received new Friend Request from " . $auth_user->first_name;
        $this->notifyCustomerSocial($friend, $title, $message);
        return $friends;
    }

    public function friendRequestStatus(array $data)
    {
        $auth_user = auth()->user();
        // FriendRequest::where('user_id', $data['user_id'])->where('friend_id', $data['user_id'])
        //SELECT * FROM `friend_requests` where ( user_id = 3 OR friend_id = 3) AND ( user_id = 18 OR friend_id = 18 );
        // $FriendRequest = FriendRequest::create([
        //     'user_id'   => $data['user_id'],
        //     'friend_id' => $auth_user->id,
        // ]);
        // dd($data);
        $user          = User::where('id',$data['user_id'])->count();
        if($user == 0)
        {
            throw new GeneralException('No User Found');
        }
        $FriendRequest = FriendRequest::where('user_id', $data['user_id'])->where('friend_id', $auth_user->id,)->update(['status' => $data['status']]);
        $auth_user->friend;
        $user_data          = User::find($data['user_id']);
        if($data['status'] == 1 ) {
            $title              =  "Accepted your friend request";
            $message            =  $auth_user->first_name . " has Accepted your friend request";
        } else {
            $title              = "Decline your friend request";
            $message            =  $auth_user->first_name . " has declined your friend request";
        }
        $this->notifyCustomerSocial($user_data, $title, $message);
        return $auth_user;
    }

    public function pendingFriendReq(array $data)
    {
        $auth_user = auth()->user();
        if( $data['request'] === 1 )
        {
            $FriendRequest =  $auth_user->pending_friends;
        }
        else
        {
            $FriendRequest =  $auth_user->incoming_friends;
        }
        return $FriendRequest;
    }

    public function giftCreditSend(array $data)
    {
        $auth_user = auth()->user();
        $receiverUser = User::find($data['user_id']);
        $receiverCreditAmount   = $receiverUser->credit_amount;
        $authUserCreditAmount   = $auth_user->credit_amount;
        $amount                 = $data['amount'];
        $stripe_customer_id     = $auth_user->stripe_customer_id;
        $stripe                 = new Stripe();
        $customer_cards         = $stripe->fetchCards($stripe_customer_id)->toArray();

        if(empty($customer_cards['data'])) {
            throw new GeneralException('Please Add Card details');
        }
        $getCusCardId   = $stripe->fetchCustomer($stripe_customer_id);
        $defaultCardId  = $getCusCardId->default_source;

        $paymentArr = [
            'amount'        => number_format($amount, 2) * 100,
            // 'currency'      => $auth_user->orders->restaurant->currency->code,
            'currency'      => 'aud',
            'customer'      => $auth_user->stripe_customer_id,
            'source'        => $defaultCardId,
            'description'   => "Gift Credit Send to ". $data['user_id']
        ];

        $payment_data   = $stripe->createCharge($paymentArr);

        $addAmountToReceiver    = number_format($receiverCreditAmount + $amount, 2);
        $receiverUser->credit_amount = $addAmountToReceiver;
        return $auth_user;
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
        $order          = Order::with([
            'order_items' => [
                'restaurant_item',
                'addons',
                'mixer'
            ]
        ])->where(['id' => $data])->first();
        $restaurant     = $order->restaurant->owners()->first();

        // Generate PDF
        $pdf        = app('dompdf.wrapper');
        $pdf->loadView('pdf.index',compact('order','restaurant'))->setPaper('3.5in', '8.5in');;
        $filename   = 'invoice_'.$order->id.'.pdf';
        $content    = $pdf->output();
        $file       = storage_path("app/public/order_pdf");
        !is_dir($file) &&
        mkdir($file, 0777, true);
        $filePath = 'public/order_pdf/' . $filename;

        //Upload PDF to storage folder
        Storage::put($filePath, $content);
        $destinationPath = asset('storage/order_pdf/').'/'.$filename;
        return $destinationPath;
    }

    public function userProfileData(array $data)
    {
        $authId = auth()->user()->id;
        // $userData = $userData = User::where('id',$data['user_id'])->with(['getMyfriend'])->first();
        $userData = User::where('id', $data['user_id'])
            ->leftJoin(DB::raw
            (
                "(SELECT
                    status AS friendship_status,
                    user_id,
                    friend_id
                FROM friendships
                WHERE (
                    user_id = {$authId}
                    OR friend_id = {$authId}
                ))  AS `friendship_status_tbl`
                "
                ),function($join)
            {
                $join->on('users.id', '=', 'friendship_status_tbl.user_id');
                $join->orOn('users.id', '=', 'friendship_status_tbl.friend_id');
            })
            ->first();

        return $userData;
    }

    /**
     * Method myFriendList
     *
     *
     * @return Collection
     */
    public function myFriendList(): Collection
    {
        $user = auth()->user();

        $sql = User::select([
            'users.*',
            'friendship_status_tbl.friendship_status AS fr'
        ])
        ->join(DB::raw
        (
            "(SELECT
                status AS friendship_status,
                user_id,
                friend_id
            FROM friendships
            WHERE (
                user_id = {$user->id}
                OR friend_id = {$user->id}
            ))  AS `friendship_status_tbl`
            "
        ), function($join)
        {
            $join->on('users.id', '=', 'friendship_status_tbl.user_id');
            $join->orOn('users.id', '=', 'friendship_status_tbl.friend_id');
        })
        ->where('users.id', '!=', $user->id)
        ->where('friendship_status_tbl.friendship_status', 1)
        ->get();

        return $sql;
    }

    public function unFriend(array $data)
    {
        $auth_user = auth()->user();
        $checkUnFriendRequest = User::where('id', $data['user_id'])
            ->leftJoin(DB::raw
            (
                "(SELECT
                    id AS friendship_id,
                    status AS friendship_status,
                    user_id,
                    friend_id
                FROM friendships
                WHERE (
                    user_id = {$auth_user->id}
                    OR friend_id = {$auth_user->id}
                ))  AS `friendship_status_tbl`
                "
                ),function($join)
            {
                $join->on('users.id', '=', 'friendship_status_tbl.user_id');
                $join->orOn('users.id', '=', 'friendship_status_tbl.friend_id');
            })
            ->first();
        
        if($data['user_id'] == 0) {
            throw new GeneralException('Please Enter valid user id you are pass 0 user id');
        }
        if(!empty($checkUnFriendRequest))
        {
            $delete = FriendRequest::where('id', $checkUnFriendRequest->friendship_id)->delete();
            $title              = $auth_user->first_name . " has unfriended you";
            $message            = $auth_user->first_name . " has unfriended you";
            $this->notifyCustomerSocial($checkUnFriendRequest, $title, $message);
        }
        return $delete;
    }
}