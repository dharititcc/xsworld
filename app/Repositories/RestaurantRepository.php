<?php namespace App\Repositories;

use App\Models\Restaurant;
use App\Models\RestaurantItem;
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
        $distance   = isset( $data['distance'] ) ? $data['distance'] : 10;

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
        return $query->whereHas('restaurant_items', function( Builder $query ) use($drinkName)
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
            'street1',
            'street2',
            'state',
            'city',
            'postcode',
            'country_id',
            'phone',
            'specialisation',
            DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(restaurants.latitude)) * cos(radians(restaurants.longitude) - radians(" . $long . "))
                + sin(radians(" .$lat. ")) * sin(radians(restaurants.latitude))) AS distance")
        ]);

        if( $restaurantName )
        {
            $query = $this->filterRestautantName($query, $restaurantName);
        }

        if( $drink_name )
        {
            $query = $this->filterItem($query, $drink_name);
        }
        $query = $this->filterRadius($query, $data);
        $query = $query->orderBy('distance');

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
        $query = RestaurantItem::with(['category', 'category.mixers', 'category.addons', 'attachment'])->where('restaurant_id', $data['restaurant_id'] )->where('category_id', $data['category_id'] )->where('type', RestaurantItem::ITEM);

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
                    // ->with([
                    //         'restaurant_item_type',
                    //         'restaurant_item_type.item_type',
                    //         'item',
                    //         'restaurant.currency'
                    //     ])
                    ->where('restaurant_id', $data['restaurant_id'] )
                    ->where('category_id', $data['category_id'] )
                    ->where('is_featured',1);

        return $query->get();
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

        $query = $user->favourite_items()
            ->with(['category', 'category.mixers', 'category.addons'])
            ->whereHas('restaurant', function($query) use($data)
            {
                return $query->where('id', $data['restaurant_id']);
            });

            if( isset($data['category_id']) )
            {
                $query->whereHas('category', function($query) use($data)
                {
                    return $query->where('id', $data['category_id']);
                });
            }

        return $query->get();
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
        $categoryId         = isset( $data['category_id'] ) ? $data['category_id'] : null;
        $query              = RestaurantItem::query()->with(['category', 'category.mixers', 'category.addons', 'restaurant']);

        if( $restaurantId )
        {
            $query->where('restaurant_id', $restaurantId)->where('is_featured', 1);
        }

        if( $categoryId )
        {
            $query->where('category_id', $categoryId);
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