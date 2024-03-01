<?php namespace App\Http\Controllers\Api\V1\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Billing\Stripe;
use App\Mail\InvoiceMail;
use App\Models\OrderSplit;
use Illuminate\Http\Request;
use App\Models\CustomerTable;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Traits\CreditPoint;
use App\Notifications\RefundNotification;
use App\Repositories\Traits\XSNotifications;
use App\Http\Controllers\Api\V1\UserController;

trait OrderStatus
{
    use CreditPoint, XSNotifications;
    /**
     * Method statusChange
     *
     * @param Request $request [explicite description]
     *
     * @return Order
     */
    public function statusChange(Request $request): Order
    { 
        // $userRepository = new UserRepository(); // Assuming you have UserRepository class
        // $userController = new UserController($userRepository);
        // $fetchCard = $userController->fetchCard();
        // $creditCardDetails = $fetchCard->getData();
        // $cardDetails = $creditCardDetails->item;
        
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

            $title      = "Kitchen ready for pickup";
            $message    = "Your Order is #".$order->id." kitchen ready for pickup";


            $titleWaiter      = "Ready for pickup";
            $messageWaiter    = "Your Order is #".$order->id." kitchen ready for pickup";
            $codeWaiter       = Order::READY_FOR_COLLECTION;

            // send notification to waiters
            $this->notifyWaiters($order, $titleWaiter, $messageWaiter, $codeWaiter);

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

            $title      = "Kitchen cancelled your order";
            $message    = "Your Order is #".$order->id." cancelled";

            $titleWaiter      = "Restaurant kitchen cancelled";
            $messageWaiter    = "Your Order is #".$order->id." kitchen cancelled";
            $codeWaiter       = Order::WAITER_CANCEL_ORDER;

            // send notification to waiters
            $this->notifyWaiters($order, $titleWaiter, $messageWaiter, $codeWaiter);
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

            $titleWaiter      = "Restaurant kitchen confirm collection";
            $messageWaiter    = "Your Order is #".$order->id." ready for collection";
            $codeWaiter       = Order::WAITER_CONFIRM_COLLECTION;

            // send notification to waiters
            $this->notifyWaiters($order, $titleWaiter, $messageWaiter, $codeWaiter);

            // send email
            Mail::to($order->user->email)->send(new InvoiceMail($order));
        }

        // send notification to customer
        $this->notifyCustomer($order, $title, $message);

        return $order;
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
        $title      = "Your money has been refunded successfully.";
        $message    = "Order Id is #".$order->id."and Refund Id is ".$refund_data->id;
        $order->update($updateArr);
        $order->user->notify(new RefundNotification($order, $refund_data->id));
        $this->notifyCustomer($order, $title, $message);

        return $order;
    }
}