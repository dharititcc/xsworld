<?php namespace App\Repositories\Traits;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait XSNotifications
{
    /**
     * Method notifyWaiters
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     *
     * @return mixed|void
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyWaiters(Order $order, string $title, string $message, int $code)
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
                    $WaiterDevicesTokensArr = $waiter->user->devices->pluck('fcm_token')->unique()->toArray();

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
                // $orderid    = $order->id;
                Log::debug("Waiter Notification Testing:  - {$order->id}");
                return waiterNotification($title, $message, $waiterDevices, $code , $order->id);
            }
        }
    }

    /**
     * Method notifyCustomer
     *
     * @param \App\Models\Order $order [explicite description]
     * @param string $title [explicite description]
     *
     * @return mixed|void
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyCustomer(Order $order, string $title, string $message)
    {
        // Customer Notify
        $customer_devices   = $order->user->devices->count() ? $order->user->devices()->pluck('fcm_token')->unique()->toArray() : [];
        $orderid            = $order->id;

        if(!empty($customer_devices))
        {
            Log::debug("Customer Notification Testing:  - {$order->id}");
            return sendNotification($title, $message, $customer_devices, $orderid);
        }
    }

    /**
     * Method notifyKitchens
     *
     * @param Order $order [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     *
     * @return mixed|void
     */
    public function notifyKitchens(Order $order, string $title, string $message)
    {
        $kitchenDevices = [];
        $kitchens = $order->restaurant->kitchens()->with(['user', 'user.devices'])->get();

        if( $kitchens->count() )
        {
            foreach( $kitchens as $kitchen )
            {
                $kitchenDevicesTokensArr = $kitchen->user->devices->pluck('fcm_token')->unique()->toArray();
                // $waiterDevices = array_merge($waiterDevices, );
                if( !empty( $kitchenDevicesTokensArr ) )
                {
                    foreach( $kitchenDevicesTokensArr as $token )
                    {
                        $kitchenDevices[] = $token;
                    }
                }
            }
        }

        if( !empty( $kitchenDevices ) )
        {
            $kitchenTitle    = $title;
            $kitchenMessage  = $message;
            Log::debug("Kitchen Notification Testing:  - {$order->id}");
            sendNotification($kitchenTitle, $kitchenMessage, $kitchenDevices, $order->id);
        }
    }

    /**
     * Method notifyBars
     *
     * @param Order $order [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     *
     * @return mixed|void
     */
    public function notifyBars(Order $order, string $title, string $message)
    {
        $bardevices     = [];
        if(isset($order->pickup_point_user_id))
        {
            $bardevices = $order->pickup_point_user->devices()->pluck('fcm_token')->unique()->toArray();
        }

        if(!empty( $bardevices ))
        {
            Log::debug("Bar Notification Testing:  - {$order->id}");
            $bar_notification   = sendNotification($title, $message, $bardevices, $order->id);
        }
    }


    /**
     * Method notifyCustomerSocial
     *
     * @param \App\Models\User $user [explicite description]
     * @param string $title [explicite description]
     *
     * @return mixed|void
     * @throws \App\Exceptions\GeneralException
     */
    public function notifyCustomerSocial(User $user, string $title, string $message)
    {
        // Customer Notify
        $customer_devices   = $user->devices->count() ? $user->devices()->pluck('fcm_token')->unique()->toArray() : [];
        $orderid            = $user->id;

        if(!empty($customer_devices))
        {
            Log::debug("Customer Notification Testing:  - {$user->id}");
            return sendNotification($title, $message, $customer_devices, $orderid);
        }
    }
}