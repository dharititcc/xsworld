<?php namespace App\Repositories;

use App\Models\Restaurant;
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
     * Method filterItems
     *
     * @param string $drink_name [explicite description]
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterItems(string $drink_name, Builder $query): Builder
    {
        return $query;
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
        $lat        = isset( $data['latitude'] ) ? $data['latitude'] : null;
        $long       = isset( $data['longitude'] ) ? $data['longitude'] : null;

        $query = $this->restaurantQuery()->with(['item_types'])->select([
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

        $query = $this->filterRadius($query, $data);

        return $query->get();
    }
}