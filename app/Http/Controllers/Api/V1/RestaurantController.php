<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\RestaurantFilterApiRequest;
use App\Http\Resources\RestaurantResource;
use App\Repositories\RestaurantRepository;

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
    /**
     * @OA\Post(
     ** path="/api/v1/restaurants",
     *   tags={"Restaurants"},
     *   summary="Get Near by Restaurants. Distance by default is 2.5K",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     * 
     *  @OA\Parameter(
     *      name="restaurant_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="drink_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="distance",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="float"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="latitude",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="longitude",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *)
     **/
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
