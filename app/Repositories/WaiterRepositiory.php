<?php namespace App\Repositories;

use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class WaiterRepositiory.
*/
class WaiterRepositiory extends BaseRepository
{
    /**
     * Method getItembyName
     *
     * @param array $data [explicite description]
     *
     * @return Collection
     */
    public function getItemByName(array $data) : Collection
    {
        $auth_waiter = auth()->user();
        // dd($auth_waiter->restaurant_waiter->restaurant_id);
        $item_name = isset($data['item_name'])  ? $data['item_name'] : null;
        $query = RestaurantItem::query()->with(['category','category.mixers','category.addons', 'restaurant', 'variations'])->where('restaurant_id',$auth_waiter->restaurant_waiter->restaurant_id);

        if($item_name) {
            $query = $query->where('name','LIKE', '%'.$item_name.'%');
        }

        return $query->get();
    }

    public function updateStatus(array $data,$isWaiter = 0)
    {
        $user   = auth()->user();
        // dd($user->restaurant_kitchen());
        if($isWaiter == 1) {
            $user->restaurant_waiter()->update($data);
        } else {
            $user->restaurant_kitchen()->update($data);
        }
        $user->refresh();
        return $user;
    }

    public function category()
    {
        $auth_waiter = auth()->user();
        // $category_id = isset($data['category_id'])  ? $data['category_id'] : null;
        $query = Category::where('restaurant_id',$auth_waiter->restaurant_waiter->restaurant_id)->get();
        return $query;
    }


    /**
     * Method getRestaurantSubCategories
     *
     * @param array $data [explicite description]
     *
     * @return Collection
     */
    public function getRestaurantSubCategories(array $data) : Collection
    {
        $restaurantId           = isset( $data['restaurant_id'] ) ? $data['restaurant_id'] : null;
        $restaurantCategoryId   = isset( $data['category_id'] ) ? $data['category_id'] : null;

        if( $restaurantId )
        {
            $restaurant = Restaurant::with(['sub_categories'])->findOrFail($restaurantId);
        }

        if( $restaurantCategoryId )
        {
            return $restaurant->sub_categories->where('parent_id', $restaurantCategoryId);
        }

        return $restaurant;
    }

    
}