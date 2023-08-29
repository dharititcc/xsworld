<?php namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
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
     * @return Collection
     */
    public function addTocart(array $data) : Collection
    {
        $user        = auth()->user();
        $restaurant  = RestaurantItem::find($data['restaurant_item_id']);
        $order       = Order::where('user_id',$user->id)->get();

        $price       = $data['price'] * $data['quantity'];

        $cart_data = [
            'user_id'               => $user->id,
            'restaurant_id'         => $restaurant->restaurant_id,
            'pickup_point_id'       => null,
            'type'                  => 1,
            'status'                => 0,
            'amount'                => $price,
            'currency_id'           => 1
        ];

        // if( $order->count() )
        // {

        // }
        // else
        // {
            $cart_data = Order::create($cart_data);

            if($cart_data){
                $cart_item_data = [
                    'order_id'              => $cart_data->id,
                    'restaurant_item_id'    => $data['restaurant_item_id'],
                    'price'                 => $data['price'],
                    'quantity'              => 1,
                    'total'                 => $price,
                ];

                $cart_items = OrderItem::create($cart_item_data);
                dd($cart_items);
            }

        // }
        // return $cart_data->get();

    }
}