<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\CreditPointsHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class BarRepository.
*/
class BarRepository extends BaseRepository
{
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
        // foreach($user->pickup_point->restaurant->main_categories as $category)
        // {
        //     if($category->name == "Drinks") {
        //         $category_id = $category->id;
        //     }
        // }

        $category_id = $this->categoryGet();

        $user->loadMissing(['pickup_point']);

        return Order::with([
            'order_items' => function($query) use($category_id){
                $query->where('category_id',$category_id);
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
        ->where(['type'=> Order::ORDER])
        // ->where(['type'=> Order::ORDER , 'status' => Order::PENDNIG])
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
            return $query->whereIn('status', [OrderItem::ACCEPTED, OrderItem::CONFIRM_PICKUP]);
        })
        ->orderBy('orders.apply_time','asc')
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
        ->whereIn('status', [Order::COMPLETED, Order::RESTAURANT_CANCELED, Order::RESTAURANT_TOXICATION, Order::CONFIRM_PICKUP])
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
        ->orderBy('orders.apply_time','asc')
        ->orderByDesc('orders.id')
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
            $points                     = $order->total * 3;
            $update['points']           = $user->points + round($points);
            $user->update($update);
            $creditArr['user_id']       = $user->id;
            $creditArr['order_id']      = $order->id;
            $creditArr['credit_point']  = $order->total;
            $creditArr['total']         = $update['points'];

            CreditPointsHistory::create($creditArr);
        }
        return $order;
    }

    public function userCreditAmountUpdated(User $user,$remaingAmount)
    {
        User::where('id', $user->id)->update(['credit_amount' => $remaingAmount]);
        return true;
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
        $order             = Order::findOrFail($order_id);
        $updateArr         = [];
        $user              = $order->user_id ? User::findOrFail($order->user_id) : auth()->user();
        $user_tokens       = $user->devices()->pluck('fcm_token')->toArray();

        if(isset($order->id))
        {
            if($status == Order::DELAY_ORDER || $status == Order::ACCEPTED)
            {
                if( isset($apply_time) && $apply_time > 0 )
                {
                    $updateArr['accepted_date']         = Carbon::now();
                    $time                               = $order->apply_time;
                    $updateArr['apply_time']            = $apply_time + $time;
                    $updateArr['last_delayed_time']     = $apply_time;
                    if(isset($order->remaining_date))
                    {
                        $old_time           = Carbon::parse($order->remaining_date);
                        $remaining_date     = $old_time->addMinutes($apply_time);
                    }
                    else
                    {
                        $current_time       = Carbon::now();
                        $remaining_date     = $current_time->addMinutes($apply_time);
                    }
                    $updateArr['remaining_date']    = $remaining_date;
                    $updateArr['status']    = $status;

                    // order items status update if the status is accepted
                    if( $status != Order::DELAY_ORDER )
                    {
                        $this->orderItemStatusUpdated($order_id, Order::ACCEPTED);
                    }

                    // update order
                    $order->update($updateArr);

                    $title                      = "Order Delay Time Changed";
                    $message                    = "Bar Delay Your Order #".$order_id;
                    $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
                }
                else
                {
                    throw new GeneralException('Apply time should greater than zero.');
                }
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
                $updateArr['completion_date']   = Carbon::now();
                $updateArr['remaining_date']    = Carbon::now();
                $this->orderItemStatusUpdated($order_id, OrderItem::COMPLETED);
                // $order = $this->allOrderCompletedlogic($order,$updateArr,$user);

                // update order data
                $order->update($updateArr);

                $title                      = "Order Status Changed";
                $message                    = "Order is Completed from Bar #".$order_id;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::RESTAURANT_CANCELED)
            {
                // RESTAURANT_CANCELED and process for refund
                $this->orderItemStatusUpdated($order_id,OrderItem::RESTAURANT_CANCELED);
                $updateArr['status']            = $status;
                $order->update($updateArr);
                $userCreditAmountBalance = $user->credit_amount;
                $refundCreditAmount = $order->credit_amount;
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                $this->userCreditAmountUpdated($user,$totalCreditAmount);
                $title                      = "Restaurant Cancled Your Order";
                $message                    = "Restaurant Cancled Your Order #".$order_id;
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::CONFIRM_PICKUP)
            {
                $updateArr['status']            = $status;
                $updateArr['served_date']       = Carbon::now();
                $this->orderItemStatusUpdated($order_id,OrderItem::CONFIRM_PICKUP);
                $order = $this->allOrderCompletedlogic($order,$updateArr,$user);
                $order->update($updateArr);
                $title                      = "Order Status Changed";
                $message                    = "Order is Confirm from Bar";
                $send_notification          = sendNotification($title,$message,$user_tokens,$order_id);
            }

            if($status == Order::RESTAURANT_TOXICATION)
            {
                // RESTAURANT_TOXICATION and process for refund
                $updateArr['status']            = $status;

                $order->update($updateArr);
                $title                      = "Restaurant Toxication Order";
                $message                    = "Restaurant Toxication Order ";
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