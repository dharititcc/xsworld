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
                    $WaiterDevicesTokensArr = $waiter->user->devices->sortByDesc('id')->pluck('fcm_token')->unique()->toArray();

                    if( !empty( $WaiterDevicesTokensArr ) )
                    {
                        foreach( $WaiterDevicesTokensArr as $token )
                        {
                            $waiterDevices[] = $token;

                            Log::debug("Waiter Notification Testing:  - {$order->id}");
                            waiterNotification($title, $message, [$token], $code , $order->id);
                        }
                    }
                }
            }

            // if( !empty( $waiterDevices ) )
            // {
            //     // $orderid    = $order->id;
            //     Log::debug("Waiter Notification Testing:  - {$order->id}");
            //     return waiterNotification($title, $message, $waiterDevices, $code , $order->id);
            // }
        }
    }

    /**
     * Method notifyCustomer
     *
     * @param Order $order [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     * @param string|null $type [explicite description]
     *
     * @return mixed|void
     */
    public function notifyCustomer(Order $order, string $title, string $message, $type = null)
    {
        // Customer Notify
        $customer_devices   = $order->user->devices->count() ? $order->user->devices()->orderBy('id', 'desc')->pluck('fcm_token')->unique()->toArray() : [];
        $orderid            = $order->id;
        $type               = isset( $type ) ? $type : User::NOTIFICATION_ORDER;

        if(!empty($customer_devices))
        {
            foreach( $customer_devices as $token )
            {
                Log::debug("Customer Notification Testing:  - {$order->id}");
                sendCustomerNotification($title, $message, [$token], $orderid, $type);
            }
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
                $kitchenDevicesTokensArr = $kitchen->user->devices->sortByDesc('id')->pluck('fcm_token')->unique()->toArray();
                // $waiterDevices = array_merge($waiterDevices, );
                if( !empty( $kitchenDevicesTokensArr ) )
                {
                    foreach( $kitchenDevicesTokensArr as $token )
                    {
                        $kitchenDevices[] = $token;

                        $kitchenTitle    = $title;
                        $kitchenMessage  = $message;
                        Log::debug("Kitchen Notification Testing:  - {$order->id}");
                        sendNotification($kitchenTitle, $kitchenMessage, [$token], $order->id);
                    }
                }
            }
        }

        // if( !empty( $kitchenDevices ) )
        // {
        //     $kitchenTitle    = $title;
        //     $kitchenMessage  = $message;
        //     Log::debug("Kitchen Notification Testing:  - {$order->id}");
        //     sendNotification($kitchenTitle, $kitchenMessage, $kitchenDevices, $order->id);
        // }
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
            $bardevices = $order->pickup_point_user->devices()->orderBy('id', 'desc')->pluck('fcm_token')->unique()->toArray();
        }

        if(!empty( $bardevices ))
        {
            foreach( $bardevices as $token )
            {
                Log::debug("Bar Notification Testing:  - {$order->id}");
                $bar_notification   = sendNotification($title, $message, [$token], $order->id);
            }
        }
    }


    /**
     * Method notifyCustomerSocial
     *
     * @param User $user [explicite description]
     * @param string $title [explicite description]
     * @param string $message [explicite description]
     *
     * @return mixed|void
     */
    public function notifyCustomerSocial(User $user, string $title, string $message)
    {
        // Customer Notify
        $customer_devices   = $user->devices->count() ? $user->devices()->orderBy('id', 'desc')->pluck('fcm_token')->unique()->toArray() : [];

        if(!empty($customer_devices))
        {
            foreach( $customer_devices as $token )
            {
                Log::debug("Customer Notification Testing:  - {$user->id}");
                socialNotification($title, $message, [$token]);
            }
        }
    }
}