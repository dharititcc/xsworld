<?php namespace App\Repositories;

use App\Exceptions\GeneralException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\User;
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

        $user->loadMissing(['latest_cart', 'latest_cart.restaurant']);

        $latestCart = $user->latest_cart;

        if( isset( $latestCart->id ) && ($latestCart->restaurant->id ==  $order['restaurant_id']) )
        {
            // check restaurant id available in the cart
            return $this->checkSameRestaurantOrder($user, $latestCart, $orderItems);
        }
        else
        {
            // new order
            return $this->createOrder($user, $order, $orderItems);
        }

        throw new GeneralException('Order request is invalid.');
    }

    /**
     * Method checkSameRestaurantOrder
     *
     * @param User $user [explicite description]
     * @param Order $order [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    private function checkSameRestaurantOrder(User $user, Order $order, array $orderItems): Order
    {
        // check if order request is available
        if( isset( $order->id ) && !empty($orderItems) )
        {
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

                    if( isset( $item['variation'] ) && !empty( $item['variation'] ) )
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
                        $newOrderItem = $this->createOrderItem($order, $variationArr);
                    }
                    else
                    {
                        $newOrderItem = $this->createOrderItem($order, $itemArr);
                    }

                    // add item in the order items table

                    // make proper mixer data for the table
                    if( isset( $item['mixer'] ) && !empty( $item['mixer'] ) )
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
                        $mixerItem = $this->createOrderItem($order, $mixerArr);
                    }

                    // make proper data for addons
                    if( isset( $item['addons'] ) && !empty( $item['addons'] ) )
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
                                $addonItem = $this->createOrderItem($order, $addonData);
                            }
                        }
                    }
                }
            }

            $order->refresh();
            $order->loadMissing(['items']);
            $order->update(['total' => $order->items->sum('total')]);

            return $order;
        }

        throw new GeneralException('Order could not be updated.');
    }

    /**
     * Method createOrder
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     * @param array $orderItems [explicite description]
     *
     * @return Order
     */
    private function createOrder(User $user, array $data, array $orderItems): Order
    {
        $restaurant = Restaurant::find($data['restaurant_id']);
        $order['user_id'] = $user->id;
        $order['restaurant_id'] = $restaurant->id;
        $order['currency_id'] = $restaurant->currency_id;

        // dd($order);
        $newOrder = Order::create($order);

        $newOrder->refresh();

        return $this->checkSameRestaurantOrder($user, $newOrder, $orderItems);
    }

    /**
     * Method createOrderItem
     *
     * @param Order $order [explicite description]
     * @param array $data [explicite description]
     *
     * @return OrderItem
     */
    private function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    /**
     * Method getCartdata
     *
     * @return Order
     */
    public function getCartdata(): ?Order
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

    /**
     * Method deleteItem
     *
     * @param array $data [explicite description]
     *
     * @return Order
     */
    function deleteItem(array $data): Order
    {
        $order_item_id  = $data['order_item_id'] ? $data['order_item_id'] : null;
        $orderItem      = OrderItem::with(['addons','mixer', 'order'])->findOrFail($order_item_id);
        $order          = $orderItem->order;

        if($orderItem->parent_item_id == null)
        {
            $data = $orderItem->addons()->delete();
            $data = $orderItem->mixer()->delete();
            $orderItem->delete();
        }
        else
        {
            $orderItem->delete();
        }

        $order->refresh();
        $order->loadMissing(['items']);
        $order->update(['total' => $order->items->sum('total')]);

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
        $order_id   = $data['order_id'] ? $data['order_id'] : null;
        $status     = $data['status'] ? $data['status'] : null;
        $order      = Order::findOrFail($order_id);

        if(isset($order->id))
        {
            $order->update(['status' => $status]);
        }

        $order->refresh();
        $order->loadMissing(['items']);

        return $order;
    }
}