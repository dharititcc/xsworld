<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\OrderSplit;
use App\Billing\Stripe;
use App\Models\CustomerTable;
use App\Repositories\Traits\CreditPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait OrderStatus
{
    use CreditPoint;
    /**
     * Method statusChange
     *
     * @param Request $request [explicite description]
     *
     * @return Order
     */
    public function statusChange(Request $request): Order
    {
        $order      = isset($request->order_id) ? Order::with(['order_split_drink', 'order_split_food'])->find($request->order_id) : null;
        $status     = $request->status;
        $title      = "Kitchen confirm order";
        $message    = "Your Order is #".$order->id." Ready for pickup";

        if($status == OrderSplit::READYFORPICKUP)
        {
            if( $order->order_split_food->update(['status' => OrderSplit::READYFORPICKUP]) )
            {
                $order->update([
                    'completion_date'   => Carbon::now()
                ]);
                if( isset( $order->restaurant_table_id ) )
                {
                    // update waiter status to Ready for collection
                    $order->update(['waiter_status' => Order::READY_FOR_COLLECTION, 'status' => Order::COMPLETED]);

                    // capture charge if order is from customer
                }
            }


            $title      = "Ready for pickup";
            $message    = "Your Order is #".$order->id." kitchen ready for pickup";
            $code       = Order::READY_FOR_COLLECTION;

        }
        elseif ($status == OrderSplit::KITCHEN_CANCELED)
        {
            if( isset( $order->restaurant_table_id ) )
            {
                # update status in ordersplit tbl
                if( $order->order_split_food->update(['status' => OrderSplit::KITCHEN_CANCELED]) )
                {
                    // update waiter status to Ready for collection
                    $order->update(['status' => Order::CUSTOMER_CANCELED, 'waiter_status' => Order::CUSTOMER_CANCELED]);
                }
                if(isset($order->charge_id) && $order->amount > 0)
                {
                    $this->refundCharge($order);
                }

                // update order split status for drink to completed
                $userCreditAmountBalance = $order->user->credit_amount;
                $refundCreditAmount = $order->credit_amount;
                $totalCreditAmount = $userCreditAmountBalance + $refundCreditAmount;
                // update user's credit amount
                $this->updateUserPoints($order->user, ['credit_amount' => $totalCreditAmount]);

                // update customer table update
                CustomerTable::where('user_id', $order->user->id)->where('order_id', $order->id)->delete();
            }
            $title      = "Restaurant kitchen cancelled";
            $message    = "Your Order is #".$order->id." kitchen cancelled";
            $code       = Order::WAITER_CANCEL_ORDER;
        }
        else if( $status == OrderSplit::KITCHEN_CONFIRM )
        {
            if( $order->order_split_food->update(['status' => OrderSplit::KITCHEN_CONFIRM]) )
            {
                $order->update([
                    'served_date'   => Carbon::now()
                ]);
                if( isset( $order->restaurant_table_id ) )
                {
                    // update waiter status to Ready for collection
                    $order->update(['waiter_status' => Order::CURRENTLY_BEING_SERVED, 'status' => Order::CONFIRM_PICKUP]);
                }
            }

            $points         = $order->total * 3;
            $totalPoints    = $order->user->points + round($points);

            $this->insertCreditPoints($order->user, [
                'model_name'    => '\App\Models\Order',
                'model_id'      => $order->id,
                'points'        => $points,
                'type'          => 1
            ]);

            // update user's points
            $this->updateUserPoints($order->user, ['points' => $totalPoints]);

            $title      = "Restaurant kitchen confirm collection";
            $message    = "Your Order is #".$order->id." ready for collection";
            $code       = Order::WAITER_CONFIRM_COLLECTION;
        }

        // send notification to waiters
        $this->notifyWaiters($order, $title, $message, $code);

        // send notification to customer
        $this->notifyCustomer($order, $title, $message);

        return $order;
    }

    /**
     * Method notifyWaiters
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyWaiters(Order $order, string $title, string $message, int $code): mixed
    {
        // send notification to waiter if table order
        if( isset( $order->restaurant_table_id ) )
        {
            $order->loadMissing([
                'restaurant',
                'restaurant.waiters'
            ]);

            $waiterDevices  = [];
            $waiters        = $order->restaurant->waiters()->with(['user', 'user.devices'])->get();

            if( $waiters->count() )
            {
                foreach( $waiters as $waiter )
                {
                    $WaiterDevicesTokensArr = $waiter->user->devices->pluck('fcm_token')->toArray();

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
                $orderid    = $order->id;
                return waiterNotification($title, $message, $waiterDevices, $orderid);
            }
        }
    }

    /**
     * Method notifyCustomer
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyCustomer(Order $order, string $title, string $message) : mixed
    {
        // Customer Notify
        $customer_devices   = $order->user->devices->count() ? $order->user->devices()->pluck('fcm_token')->toArray() : [];
        $orderid            = $order->id;

        if(!empty($customer_devices))
        {
            return sendNotification($title, $message, $customer_devices, $orderid);
        }
        else
        {
            throw new GeneralException('Device Token not Found.');
        }
    }

    /**
     * Method refundCharge
     *
     * @param Order $order [explicite description]
     *
     * @return Order
     */
    public function refundCharge(Order $order): Order
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
}