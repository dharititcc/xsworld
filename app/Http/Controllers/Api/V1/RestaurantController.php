<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\RestaurantFilterApiRequest;
use App\Http\Resources\RestaurantResource;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RestaurantController extends APIController
{
    /** @var \App\Repositories\RestaurantRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param RestaurantRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(RestaurantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method index
     *
     * @param \App\Http\Requests\RestaurantFilterApiRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RestaurantFilterApiRequest $request)
    {
        $restaurants = $this->repository->getRestaurants($request->validated());

        if( $restaurants->count() )
        {
            // dd($restaurants);
            return $this->respondSuccess('Restaurant Found.', RestaurantResource::collection($restaurants));
        }

        return $this->respondWithError('Restaurant not found.');
    }
}
