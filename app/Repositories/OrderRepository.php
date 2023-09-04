<?php namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Repositories\BaseRepository;
use Hamcrest\Type\IsBoolean;
use Illuminate\Support\Collection;
use Nette\Utils\Strings;

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
    public function addTocart(array $data)
    {
        $user        = auth()->user();
        $restaurant  = RestaurantItem::find($data['restaurant_item_id']);
        $order       = Order::where('user_id',$user->id)->get();

        $price       = $data['price'] * $data['quantity'];

        $cart_data = [
            'user_id'                => $user->id,
            'restaurant_id'          => $restaurant->restaurant_id,
            'pickup_point_id'        => 2,                              // TODO : add dynamic id
            'pickup_point_user_id'   => 5,                              // TODO : add dynamic id
            'type'                   => Order::CART,
            'user_payment_method_id' => $user->payment_methods->where('name', 'Cash')->pluck('id')[0],
            'status'                 => Order::PENDNIG,
            'amount'                 => $price
            // 'currency_id'         => 1
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
            return true;
        }

        return false;
        // }
        // return $cart_data->get();

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
            'items'
        ])->where('user_id',$user->id)->get();

        return $order;
    }
}