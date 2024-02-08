<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EventTableController extends Controller
{
    /** @var \App\Repositories\RestaurantRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\RestaurantRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(RestaurantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $input = $request->all();
        $input['type'] = Restaurant::EVENT;

        return DataTables::of($this->repository->getRestaurantEventDatatable($input))
            ->escapeColumns(['id'])
            ->editColumn('image', function(Restaurant $restaurant)
            {
                return "<img src='{$restaurant->image}' width='30' />";
            })
            ->editColumn('country', function(Restaurant $restaurant)
            {
                return $restaurant->country->name;
            })
            ->addColumn('actions', function(Restaurant $restaurant)
            {
                return $restaurant->action_buttons;
            })
            ->orderColumn('country', function ($query, $order) {
                $query->orderBy('restaurants.country_id', $order);
            })
            ->make(true);
    }
}
