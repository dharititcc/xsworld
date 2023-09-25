<?php namespace App\Repositories;

use App\Billing\Stripe;
use App\Models\Order;
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

        $user->loadMissing(['pickup_point']);

        return Order::with([
            'order_items',
            'order_items.addons',
            'order_items.mixer'
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

        $user->loadMissing(['pickup_point']);

        $order       = $this->orderQuery()
        ->where(['type'=> Order::ORDER , 'status' => Order::PENDNIG])
        ->orderBy('id','desc')
        ->get();

        return $order;
    }

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
        ->whereIn('status', [Order::ACCEPTED, Order::DELAY_ORDER])
        ->orderBy('id','desc')
        ->get();

        return $order;
    }

    /**
     * Method getCompletedOrder
     *
     * @return Collection
     */
    public function getCompletedOrder() : Collection
    {
        $user = auth()->user();

        $user->loadMissing(['pickup_point']);

        $order       = $this->orderQuery()
        ->where('type', Order::ORDER)
        ->whereIn('status', [Order::COMPLETED, Order::RESTAURANT_CANCELED, Order::RESTAURANT_TOXICATION, Order::CONFIRM_PICKUP])
        ->orderBy('id','desc')
        ->get();

        return $order;
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
        ->orderBy('id','desc')
        ->get();

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
            if($status == Order::ACCEPTED)
            {
                $updateArr['accepted_date'] = Carbon::now();
                $updateArr['status']        = $status;
            }

            if( isset($apply_time) )
            {
                $updateArr['apply_time'] = $apply_time;
            }

            if( $status != Order::ACCEPTED )
            {
                $updateArr['status']   = $status;
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
            }

            if($status == Order::RESTAURANT_CANCELED)
            {
                // RESTAURANT_CANCELED and process for refund
                $updateArr['status']            = $status;
            }

            if($status == Order::RESTAURANT_TOXICATION)
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