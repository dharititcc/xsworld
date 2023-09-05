<?php namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use Nette\Utils\Arrays;

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
            $restaurant = Restaurant::find($order['restaurant_id']);
            $order['user_id'] = $user->id;
            $order['currency_id'] = $restaurant->currency_id;

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

                    if( !empty( $item['variation'] ) )
                    {
                        $variationArr = [
                            'restaurant_item_id'    => $item['item_id'],
                            'parent_item_id'        => null,
                            'variation_id'          => $item['variation']['id'],
                            'quantity'              => $item['variation']['quantity'],
                            'price'                 => $item['variation']['price'],
                            'type'                  => RestaurantItem::ITEM,
                            'total'                 => $item['variation']['price'] * $item['variation']['quantity']
                        ];

                        // add variation in the order items table
                        $newOrderItem = $newOrder->items()->create($variationArr);
                    }
                    else
                    {
                        $newOrderItem = $newOrder->items()->create($itemArr);
                    }

                    // add item in the order items table

                    // make proper mixer data for the table
                    if( isset( $item['mixer'] ) )
                    {
                        $mixerArr = [
                            'restaurant_item_id'=> $item['mixer']['id'],
                            'parent_item_id'    => $newOrderItem->id,
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
                                    'parent_item_id'        => $newOrderItem->id,
                                    'price'                 => $addon['price'],
                                    'type'                  => RestaurantItem::ADDON,
                                    'quantity'              => $addon['quantity'],
                                    'total'                 => $addon['quantity'] * $addon['price']
                                ];

                                // add addon in the order items table
                                $newOrder->items()->create($addonData);
                            }
                        }
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
     * @return Order
     */
    public function getCartdata(): Order
    {
        $user        = auth()->user();

        $user->loadMissing(['latest_cart']);

        return $user->latest_cart;
    }

    /**
     * Method getOrderdata
     *
     * @return Collection
     */
    function getOrderdata() : Collection
    {
        $user        = auth()->user();
        $order       = Order::with([
            'order_items',
            'order_items.addons',
            'order_items.mixer'
        ])->where('user_id',$user->id)->where('type',Order::ORDER)->get();

        return $order;

    }

    /**
     * Method getCartCount
     *
     * @return array
     */
    function getCartCount() : array
    {
        $user       = auth()->user();

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant', 'latest_cart.order_items']);

        $cart       = [
            'cart_count'    => $user->latest_cart->order_items->count(),
            'restaurant_id' => $user->latest_cart->restaurant->id
        ];

        return $cart;
    }
}