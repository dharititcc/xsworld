<?php namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\PickupPoint;
use App\Models\RestaurantItem;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

/**
 * Class OrderRepository.
*/
class OrderRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = Order::class;

    /**
     * Method addTocart
     *
     * @param array $data [explicite description]
     *
     * @return mixed
     */
    public function addTocart(array $data)
    {
        $user       = auth()->user();
        $order      = isset( $data['order'] ) ? $data['order'] : [];
        $orderItems = isset( $data['order_items'] ) ? $data['order_items'] : [];

        // check if order request is available
        if( !empty($order) && !empty($orderItems) )
        {
            // dd($order);
            $pickupPoint = PickupPoint::find($order['pickup_point_id']);
            $order['pickup_point_id'] = $pickupPoint->id;
            $order['user_id'] = $user->id;
            $order['pickup_point_user_id'] = $pickupPoint->user_id;

            // dd($order);
            $newOrder = Order::create($order);

            if( !empty($orderItems) )
            {
                foreach( $orderItems as $item )
                {
                    // make proper item array for the table
                    $itemArr = [
                        'restaurant_item_id'    => $item['item_id'],
                        'category_id'           => $item['category_id'],
                        'price'                 => $item['price'],
                        'quantity'              => $item['quantity'],
                        'type'                  => RestaurantItem::ITEM,
                        'total'                 => $item['quantity'] * $item['price']
                    ];
                    // add item in the order items table
                    $newOrder->items()->create($itemArr);

                    // make proper mixer data for the table
                    if( isset( $item['mixer'] ) )
                    {
                        $mixerArr = [
                            'restaurant_item_id'=> $item['mixer']['id'],
                            'price'             => $item['mixer']['price'],
                            'type'              => RestaurantItem::MIXER,
                            'quantity'          => $item['mixer']['quantity'],
                            'total'             => $item['mixer']['quantity'] * $item['mixer']['price']
                        ];

                        // add mixer in the order items table
                        $newOrder->items()->create($mixerArr);
                    }

                    // make proper data for addons
                    if( isset( $item['addons'] ) )
                    {
                        $addons = $item['addons'];

                        if( !empty( $addons ) )
                        {
                            foreach( $addons as $addon )
                            {
                                $addonData = [
                                    'restaurant_item_id'    => $addon['id'],
                                    'restaurant_item_id'    => $addon['price'],
                                    'type'                  => RestaurantItem::ADDON,
                                    'quantity'              => $addon['quantity'],
                                    'total'                 => $addon['quantity'] * $addon['price']
                                ];

                                // add addon in the order items table
                                $newOrder->items()->create($addonData);
                            }
                        }
                    }

                    // make proper data for variations if any
                    if( !empty( $item['variation'] ) )
                    {
                        $variationArr = [
                            'restaurant_item_id'    => $item['item_id'],
                            'variation_id'          => $item['variation']['id'],
                            'quantity'              => $item['variation']['quantity'],
                            'price'                 => $item['variation']['price'],
                            'type'                  => RestaurantItem::ITEM,
                            'total'                 => $item['variation']['price'] * $item['variation']['quantity']
                        ];

                        // add variation in the order items table
                        $newOrder->items()->create($variationArr);
                    }
                }
            }

            $newOrder->refresh();
            $newOrder->loadMissing(['items']);
            $newOrder->update(['total' => $newOrder->items->sum('total')]);

            return $newOrder;
        }

        throw new GeneralException('Order request is invalid.');
    }

    /**
     * Method getCartdata
     *
     * @return Collection
     */
    public function getCartdata() : Collection
    {
        $user        = auth()->user();
        $order       = Order::with([
            'items',
            'items.variations'
        ])->where('user_id',$user->id)->get();

        return $order;
    }
}