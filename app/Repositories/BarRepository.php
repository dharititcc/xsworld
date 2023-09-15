<?php namespace App\Repositories;

use App\Models\Order;
use App\Repositories\BaseRepository;
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
        ])->where(['type'=> Order::ORDER , 'status' => Order::COMPLETED])->get();

        return $order;
    }
}