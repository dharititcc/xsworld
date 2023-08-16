<?php namespace App\Repositories;

use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantItem;
use App\Models\RestaurantItemType;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class RestaurantRepository.
*/
class RestaurantRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Restaurant::class;

    /**
     * Method restaurantQuery
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function restaurantQuery(): Builder
    {
        return Restaurant::query();
    }

    /**
     * Method filterRadius
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterRadius(Builder $query, array $data): Builder
    {
        $distance   = isset( $data['distance'] ) ? $data['distance'] : 2.5;

        return $query->having('distance', '<=', $distance);
    }

    /**
     * Method filterRestautantName
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     * @param string $restaurantName [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterRestautantName(Builder $query, string $restaurantName): Builder
    {
        return $query->where('name', 'LIKE', '%'.$restaurantName.'%');
    }

    /**
     * Method filterItem
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     * @param string $drinkName [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterItem(Builder $query, string $drinkName): Builder
    {
        return $query->whereHas('items', function( Builder $query ) use($drinkName)
        {
            return $query->where('name', 'LIKE', '%'.$drinkName.'%');
        });
    }

    /**
     * Method getRestaurants
     *
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRestaurants(array $data):Collection
    {
        $lat            = isset( $data['latitude'] ) ? $data['latitude'] : null;
        $long           = isset( $data['longitude'] ) ? $data['longitude'] : null;
        $restaurantName = isset( $data['restaurant_name'] ) ? $data['restaurant_name'] : null;
        $drink_name     = isset( $data['drink_name'] ) ? $data['drink_name'] : null;

        $query = $this->restaurantQuery()->with(['categories','pickup_points'])->select([
            'id',
            'name',
            'latitude',
            'longitude',
            'address',
            'phone',
            'specialisation',
            DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(restaurants.latitude)) * cos(radians(restaurants.longitude) - radians(" . $long . "))
                + sin(radians(" .$lat. ")) * sin(radians(restaurants.latitude))) AS distance")
        ]);

        if( !$restaurantName )
        {
            $query = $this->filterRadius($query, $data);
        }

        if( $restaurantName )
        {
            $query = $this->filterRestautantName($query, $restaurantName);
        }

        if( $drink_name )
        {
            $query = $this->filterItem($query, $drink_name);
        }

        return $query->get();
    }

    /**
     * Method getRestaurantItems
     *
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    public function getRestaurantItems(array $data):Collection
    {
        $query = RestaurantItem::with(['restaurant_item_type', 'restaurant_item_type.item_type','item'])->where('restaurant_id', $data['restaurant_id'] )->where('restaurant_item_type_id', $data['item_type_id'] );

        return $query->get();
    }

    /**
     * Method getRestaurantItemsFeatured
     *
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    public function getRestaurantItemsFeatured(array $data):Collection
    {
        $query = RestaurantItem::query()
                    ->with([
                            'restaurant_item_type',
                            'restaurant_item_type.item_type',
                            'item',
                            'restaurant.currency'
                        ])
                    ->where('restaurant_id', $data['restaurant_id'] )
                    ->where('restaurant_item_type_id', $data['item_type_id'] )
                    ->where('is_featured',1);

        return $query->get();
    }

    /**
     * Method getRestaurantCategories
     *
     * @param array $data [explicite description]
     *
     * @return \App\Model\RestaurantItemType
     *
     */
    public function getRestaurantItemTypes(array $data): RestaurantItemType
    {
        $restaurantItemType = isset( $data['item_type_id'] ) ? $data['item_type_id'] : null;
        $restaurantId       = isset( $data['restaurant_id'] ) ? $data['restaurant_id'] : null;
        $query              = RestaurantItemType::query()->with(['item_type']);

        if( $restaurantItemType )
        {
            $query->where('item_type_id', $restaurantItemType);
        }

        if( $restaurantId )
        {
            $query->where('restaurant_id', $restaurantId);
        }
        // echo common()->formatSql($query);die;
        return $query->first();
    }

    /**
     * Method getuserFavouriteItems
     *
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getuserFavouriteItems(array $data) : Collection
    {
        $user = auth()->user();

        return $user->favourite_items()->with(['restaurant' => function($query) use($data){
            $query->where('id', $data['restaurant_id']);
        }])->get();
    }

    /**
     * Method getFeaturedItems
     *
     * @param array $data [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedItems(array $data) : Collection
    {
        $restaurantId       = isset( $data['restaurant_id'] ) ? $data['restaurant_id'] : null;
        $query              = RestaurantItem::query();

        if( $restaurantId )
        {
            $query->where('restaurant_id', $restaurantId)->where('is_featured', 1);
        }

        return $query->get();
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