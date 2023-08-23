<?php namespace App\Repositories;

use App\Models\Order;
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

        $price       = $data['price'] * $data['quantity'];
        dd($price);
        $cart_data = [
            'user_id'               => $user->id,
            'restaurant_id'         => $restaurant->restaurant_id,
            'pickup_point_id'       => null,
            'type'                  => 0,
            'amoount'               => $price
        ];
    }
}