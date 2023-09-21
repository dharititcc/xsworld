<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Models\Order;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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
     * Method getIncomingOrder
     *
     * @return Collection
     */
    public function getIncomingOrder() : Collection
    {
        $order       = Order::with([
            'order_items',
            'order_items.addons',
            'order_items.mixer'
        ])->where(['type'=> Order::ORDER , 'status' => Order::PENDNIG])->orderBy('id','desc')->get();

        return $order;
    }

    /**
     * Method getConfirmedOrder
     *
     * @return Collection
     */
    public function getConfirmedOrder() : Collection
    {
        $order       = Order::with([
            'order_items',
            'order_items.addons',
            'order_items.mixer'
        ])->where(['type'=> Order::ORDER , 'status' => Order::ACCEPTED])->orderBy('id','desc')->get();

        return $order;
    }

    /**
     * Method getCompletedOrder
     *
     * @return Collection
     */
    public function getCompletedOrder() : Collection
    {
        $order       = Order::with([
            'order_items',
            'order_items.addons',
            'order_items.mixer'
        ])->where(['type'=> Order::ORDER ])->whereIn(['status',[Order::COMPLETED,Order::DELAY_ORDER]])->orderBy('id','desc')->get();

        return $order;
    }

    /**
     * Method updateOrder
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function updateOrder(array $data) : Order
    {
        $order_id          = $data['order_id'] ? $data['order_id'] : null;
        $status            = $data['status'] ? $data['status'] : null;
        $apply_time        = $data['apply_time'] ? $data['apply_time'] : null;
        $order             = Order::findOrFail($order_id);
        $updateArr         = [];

        if(isset($order->id))
        {
            if($status == 1)
            {
                $updateArr['accepted_date'] = Carbon::now();
                $updateArr['status']        = $status;
            }

            if( isset($apply_time) )
            {
                $updateArr['apply_time'] = $apply_time;
            }

            if( $status != 1 )
            {
                $updateArr['status']   = $status;
            }

            if($status == 3)
            {
                $stripe                         = new Stripe();
                $payment_data                   = $stripe->captureCharge($order->charge_id);
                $updateArr['status']            = $status;
                $updateArr['transaction_id']    = $payment_data->balance_transaction;
            }

            if($status == 4)
            {
                // RESTAURANT_CANCELED and process for refund
                $updateArr['status']            = $status;
            }

            if($status == 6)
            {
                // RESTAURANT_TOXICATION and process for refund
                $updateArr['status']            = $status;
            }

            $order->update($updateArr);
        }

        $order->refresh();
        $order->loadMissing(['items']);

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
}