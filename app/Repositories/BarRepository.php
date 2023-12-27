<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\CreditPointsHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\CreditPoint;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class BarRepository.
*/
class BarRepository extends BaseRepository
{
    use CreditPoint;
    /**
    * Associated Repository Model.
    */
    const MODEL = Order::class;

    /**
     * Method orderQuery
     *
     * @return Builder
     */
    private function orderQuery(): Builder
    {
        $user = auth()->user();

        $category_id = $this->categoryGet();

        $user->loadMissing(['pickup_point']);

        return Order::with([
            'user',
            'restaurant_pickup_point',
            'order_items' => function($query) use($category_id){
                $query->where('category_id',$category_id);
            },
            'order_splits' => function($query) use($category_id)
            {
                $query->where('category_id', $category_id);
            },
            'order_items.addons',
            'order_items.mixer',
            'order_items.category'
        ])->where('pickup_point_id', $user->pickup_point->id);
    }

    /**
     * Method getIncomingOrder
     *
     * @return Collection
     */
    public function getIncomingOrder() : Collection
    {
        $user = auth()->user();
        // dd($user);

        $user->loadMissing(['pickup_point']);
        $category_id = $this->categoryGet();

        $orders       = $this->orderQuery()
        ->whereHas('order_items', function($query) use($category_id)
        {
            $query->where('category_id',$category_id)
                ->where('status', OrderItem::PENDNIG);
        })
        // ->where(['type'=> Order::ORDER])
        ->where(['type'=> Order::ORDER , 'status' => Order::PENDNIG])
        ->orderBy('id','desc')
        ->get();

        return $orders;
    }

    /**
     * Method checkOrderType
     *
     *
     * @return Collection
     */
    // public function checkOrderType(Order $order)
    // {
    //     dd('Hii'.$order);
    // }

    /**
     * Method getConfirmedOrder
     *
     * @return Collection
     */
    public function getConfirmedOrder() : Collection
    {
        $user = auth()->user();

        $user->loadMissing(['pickup_point']);

        $order       = $this->orderQuery()
        ->where(['type'=> Order::ORDER ])
        // ->whereIn('status', [Order::ACCEPTED, Order::DELAY_ORDER])
        ->whereHas('order_items', function($query)
        {
            return $query->whereIn('status', [OrderItem::ACCEPTED]);
        })
        ->orderBy('orders.remaining_date','asc')
        ->orderByDesc('orders.id')
        ->get();

        return $order;
    }

    /**
     * Method getCompletedOrder
     * @param array $data
     *
     * @return array
     * @throws \App\Exceptions\GeneralException
     */
    public function getCompletedOrder(array $data) : array
    {
        $page   = isset($data['page']) ? $data['page'] : 1;
        $limit  = isset($data['limit']) ? $data['limit'] : 10;
        $text   = isset($data['text']) ? $data['text'] : null;
        $user   = auth()->user();

        $user->loadMissing(['pickup_point']);

        $order       = $this->orderQuery()
        ->where('type', Order::ORDER)
        ->whereIn('status', [Order::RESTAURANT_TOXICATION, Order::CONFIRM_PICKUP, Order::DENY_ORDER])
        ->orderBy('id','desc');
        // ->get();

        $total = $order->count();
        $order->limit($limit)->offset(($page - 1) * $limit)->orderBy('id','desc');

        $orders = $order->get();
        if( $orders->count() )
        {
            $orderData = [
                'total_orders'   => $total,
                'orders'         => $orders
            ];

            return $orderData;
        }

        throw new GeneralException('There is no order found.');
    }

    /**
     * Method getBarCollections
     *
     * @return Collection
     */
    public function getBarCollections() : Collection
    {

        $user = auth()->user();

        $user->loadMissing(['pickup_point']);

        $order       = $this->orderQuery()
        ->where('type', Order::ORDER)
        ->whereIn('status', [Order::COMPLETED])
        // ->orderBy('orders.completion_date', 'asc')
        // ->orderBy('orders.remaining_date','asc')
        ->orderBy('orders.id','asc')
        ->get();

        return $order;
    }

    /**
     * Method orderItemStatusUpdated
     *
     * @param $order_id $order_id [explicite description]
     * @param $status $status [explicite description]
     *
     * @return bool
     */
    public function orderItemStatusUpdated($order_id, $status): bool
    {
        return OrderItem::where('order_id', $order_id)->where('category_id', $this->categoryGet())->update(['status' => $status]);
    }

    public function allOrderCompletedlogic(Order $order, $updateArr , $user)
    {
        $totalItemCount = OrderItem::where('order_id',$order->id)->whereNotNull('category_id')->count();

        $totalCompletedItem = OrderItem::where('order_id',$order->id)->where('status', OrderItem::CONFIRM_PICKUP)->count();
        if($totalItemCount === $totalCompletedItem) {
            foreach($order->order_items as $orderitem)
            {
                if($orderitem->status == OrderItem::CONFIRM_PICKUP ) {
                    $order->update($updateArr);
                    $order->refresh();
                    $order->loadMissing(['items']);
                }
            }
            // point added to user account
            // $points                     = $order->total * 3;
            // $update['points']           = $user->points + round($points);
            // $user->update($update);
            // $creditArr['user_id']       = $user->id;
            // $creditArr['order_id']      = $order->id;
            // $creditArr['credit_point']  = $order->total;
            // $creditArr['total']         = $update['points'];

            // add credit point history
            $points         = $order->total * 3;
            $totalPoints    = $user->points + round($points);


            $this->insertCreditPoints($user, [
                'model_name'    => '\App\Models\Order',
                'model_id'      => $order->id,
                'points'        => $points,
                'type'          => 1
            ]);

            // update user's points
            $this->updateUserPoints($user, ['points' => $totalPoints]);
        }
        return $order;
    }

    /**
     * Method updateStatusOrder
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function updateStatusOrder(array $data) : Order
    {
        $order_id          = $data['order_id'] ? $data['order_id'] : null;
        $status            = $data['status'] ? $data['status'] : null;
        $apply_time        = $data['apply_time'] ? $data['apply_time'] : null;
        $order             = Order::with(['restaurant'])->findOrFail($order_id);
        $updateArr         = [];
        $user              = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $user_tokens       = $user->devices()->pluck('fcm_token')->toArray();

        if(isset($order->id))
        {
            if( $apply_time )
            {
                // update status to accepted or delay order
                $this->updateAcceptedDelayStatus($status, $apply_time, $order, $user_tokens);
            }

            if($status == Order::COMPLETED)
            {
                if( $order->charge_id )
                {
                    $stripe                         = new Stripe();
                    $payment_data                   = $stripe->captureCharge($order->charge_id);
                    $updateArr['transaction_id']    = $payment_data->balance_transaction;
                }
                $updateArr['status']            = $status;
                $updateArr['completion_date']   = Carbon::now()->format('Y-m-d H:i:s');
                $updateArr['remaining_date']    = Carbon::now();
                $this->orderItemStatusUpdated($order_id, OrderItem::COMPLETED);
                // $order = $this->allOrderCompletedlogic($order,$updateArr,$user);

                // update order data
                $order->update($updateArr);

                $title                      = $order->restaurant->name. " is processing your order";
                $message                    = "Your Order #".$order_id." is completed by ".$order->restaurant->name;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::RESTAURANT_CANCELED)
            {
                // RESTAURANT_CANCELED and process for refund
                $this->orderItemStatusUpdated($order_id,OrderItem::RESTAURANT_CANCELED);
                $updateArr['status']            = $status;
                if($order->charge_id)
                {
                    $this->refundCharge($order);
                }
                $order->update($updateArr);
                $userCreditAmountBalance = $user->credit_amount;
                $refundCreditAmount = $order->credit_amount;
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $totalCreditAmount]);
                $title                      = $order->restaurant->name. " is processing your order";
                $message                    = "Your Order #".$order_id." is canceled by".$order->restaurant->name;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::CONFIRM_PICKUP)
            {
                $updateArr['status']            = $status;
                $updateArr['served_date']       = Carbon::now();
                $this->orderItemStatusUpdated($order_id,OrderItem::CONFIRM_PICKUP);
                $order = $this->allOrderCompletedlogic($order,$updateArr,$user);
                $order->update($updateArr);
                $title                      = $order->restaurant->name. " is processing your order";
                $message                    = "Your Order #".$order_id." is pick up from the ".$order->restaurant->name;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::RESTAURANT_TOXICATION)
            {
                // RESTAURANT_TOXICATION and process for refund
                $updateArr['status']            = $status;
                if(isset($order->charge_id))
                {
                    $this->refundCharge($order);
                }
                $order->update($updateArr);
                $userCreditAmountBalance = $user->credit_amount;
                $refundCreditAmount = $order->credit_amount;
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $totalCreditAmount]);
                $title                      = $order->restaurant->name. " is processing your order";
                $message                    = "Your Order #".$order_id." is intoxicated by ".$order->restaurant->name;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::DENY_ORDER)
            {
                // DENY_ORDER and process for refund
                $updateArr['status']            = $status;

                if(isset($order->charge_id) && $order->amount > 0)
                {
                    $this->refundCharge($order);
                }

                $order->update($updateArr);
                $userCreditAmountBalance = $user->credit_amount;
                $refundCreditAmount = $order->credit_amount;
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                // update user's credit amount
                $this->updateUserPoints($user, ['credit_amount' => $totalCreditAmount]);
                $title                      = $order->restaurant->name. " is processing your order";
                $message                    = "Your Order #".$order_id." is denied by ".$order->restaurant->name;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            // if($order->order_category_type == 2) {

            // } else {
            //     $order->update($updateArr);
            //     $order->refresh();
            //     $order->loadMissing(['items']);
            // }
        }

        return $order;
    }

    /**
     * Method updateAcceptedDelayStatus
     *
     * @param int $status [explicite description]
     * @param int $apply_time [explicite description]
     * @param Order $order [explicite description]
     * @param User $user [explicite description]
     * @param array $user_tokens [explicite description]
     *
     * @return void
     */
    public function updateAcceptedDelayStatus(int $status, int $apply_time, Order $order, array $user_tokens)
    {
        if($status == Order::DELAY_ORDER || $status == Order::ACCEPTED)
        {
            if( isset($apply_time) && $apply_time > 0 )
            {
                $currentTime        = Carbon::now();
                $currentTimeClone   = $currentTime->clone();

                if( $status == Order::ACCEPTED )
                {
                    $orderArr = [
                        'apply_time'        => $apply_time,
                        'accepted_date'     => $currentTime,
                        'remaining_date'    => $currentTimeClone->addMinutes($apply_time),
                        'last_delayed_time' => $apply_time,
                        'status'            => Order::ACCEPTED
                    ];

                    // update order
                    $order->update($orderArr);

                    // send notification for accepted
                    $this->orderItemStatusUpdated($order->id, Order::ACCEPTED);

                    $title                      = $order->restaurant->name. " is processing your order";
                    $message                    = $order->restaurant->name. " has accepted your order #".$order->id;
                    $send_notification          = sendNotification($title,$message,$user_tokens,$order->id);
                }

                if( $status == Order::DELAY_ORDER )
                {
                    // check current time is past
                    $remainingTime  = Carbon::parse($order->remaining_date);
                    $remTime        = $remainingTime->clone();

                    // dd($remainingTime <= $currentTimeClone);
                    if( $remainingTime <= $currentTimeClone )
                    {
                        // if true then calculate apply time from current time
                        $remainingTimeDb = $currentTimeClone->addMinutes($apply_time);
                    }
                    else
                    {
                        $remainingTimeDb = $remTime->addMinutes($apply_time);
                        // dd($remainingTimeDb);
                    }
                    // dd($remainingTimeDb);
                    $orderArr = [
                        'apply_time'        => $apply_time + $order->apply_time,
                        'last_delayed_time' => $order->apply_time,
                        'remaining_date'    => $remainingTimeDb,
                        'status'            => Order::DELAY_ORDER
                    ];

                    // // update order
                    $order->update($orderArr);

                    // send notification for delay order
                    $title                      = $order->restaurant->name. " is processing your order";
                    $message                    = "Your Order #".$order->id." is delayed by ".$order->restaurant->name;
                    $send_notification          = sendNotification($title,$message,$user_tokens,$order->id);
                }
            }
            else
            {
                throw new GeneralException('Apply time should greater than zero.');
            }
        }
    }

    public function retrieveCharge(string $charge)
    {
        $stripe = new Stripe();

        return $stripe->retrieveCharge($charge);
    }

    /**
     * Method refundCharge
     *
     * @param Order $order [explicite description]
     *
     * @return Order
     */
    function refundCharge(Order $order): Order
    {
        $stripe            = new Stripe();
        $refundArr = [
            'charge'       => $order->charge_id,
        ];
        $refund_data                = $stripe->refundCreate($refundArr);
        $updateArr['refunded_id']   = $refund_data->id;
        $order->update($updateArr);

        return $order;
    }

    /**
     * Method updateStatus
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function updateStatus(array $data)
    {
        $user   = auth()->user();
        $user->pickup_point()->update($data);
        $user->refresh();
        return $user;
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
        $user = auth()->user();
        $user->loadMissing([
            'pickup_point',
            'pickup_point.restaurant',
            'pickup_point.restaurant.main_categories'
        ]);

        foreach($user->pickup_point->restaurant->main_categories as $category)
        {
            if($category->name == "Drinks") {
                $drinkcategory_id = $category->id;
            } else {
                $foodCategory_id = $category->id;
            }
        }
        return $drinkcategory_id;
    }

}