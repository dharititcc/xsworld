<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\OrderSplit;
use Illuminate\Http\Request;

trait OrderStatus
{
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

        if($status == OrderSplit::READYFORPICKUP)
        {
            if( $order->order_split_food->update(['status' => $status]) )
            {
                if( isset( $order->restaurant_table_id ) )
                {
                    // update waiter status to Ready for collection
                    $order->update(['waiter_status' => Order::READY_FOR_COLLECTION]);
                }
            }


            $title  = "Ready for pickup";
        }
        else
        {
            if( $order->order_split_food->update(['status' => OrderSplit::KITCHEN_CONFIRM]) )
            {
                if( isset( $order->restaurant_table_id ) )
                {
                    // update waiter status to Ready for collection
                    $order->update(['waiter_status' => Order::CURRENTLY_BEING_SERVED]);
                }
            }
        }

        // send notification to waiters
        $this->notifyWaiters($order, $title);

        // send notification to customer
        $this->notifyCustomer($order, $title);

        return $order;
    }

    /**
     * Method notifyWaiters
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyWaiters(Order $order, string $title): bool
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
                $message    = "Your Order is #".$order->id." Ready for pickup";
                $orderid    = $order->id;
                return sendNotification($title, $message, $waiterDevices, $orderid);
            }
        }
    }

    /**
     * Method notifyCustomer
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyCustomer(Order $order, string $title) : bool
    {
        // Customer Notify
        $customer_devices   = $order->user->devices->count() ? $order->user->devices()->pluck('fcm_token')->toArray() : [];
        $message            = "Your Order is #".$order->id." Ready for pickup";
        $orderid            = $order->id;
        if(!empty($customer_devices))
        {
            return sendNotification($title,$message,$customer_devices,$orderid);
        }
        else
        {
            throw new GeneralException('Device Token not Found.');
        }
    }
}