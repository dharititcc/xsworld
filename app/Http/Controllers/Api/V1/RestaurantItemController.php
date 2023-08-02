<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RestaurantItemsRequest;
use App\Http\Resources\RestaurantItemsResource;
use App\Repositories\RestaurantRepository;

class RestaurantItemController extends APIController
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
     ** path="/api/v1/restaurants/items",
     *   tags={"Restaurants Items"},
     *   summary="Get Restaurants Items.",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *
     *  @OA\Parameter(
     *      name="restaurant_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="item_type_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="application_version",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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
    public function index(RestaurantItemsRequest $request)
    {
        $restaurantsitems = $this->repository->getRestaurantItems($request->validated());

        if( $restaurantsitems->count() )
        {
            return $this->respondSuccess('Restaurant Items Found.', RestaurantItemsResource::collection($restaurantsitems));
        }

        return $this->respondWithError('Restaurant Items not found.');
    }
}
