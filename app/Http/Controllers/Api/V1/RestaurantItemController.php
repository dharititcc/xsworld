<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RestaurantItemsRequest;
use App\Http\Requests\SingleItemRequest;
use App\Http\Resources\RestaurantItemsResource;
use App\Http\Resources\RestaurantItemTypesResources;
use App\Repositories\RestaurantRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

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
     *   tags={"Restaurants"},
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
     *       name="category_id",
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
    public function index(RestaurantItemsRequest $request)
    {
        $input = $request->validated();
        $input['is_available'] = 1;
        $restaurantsitems           = $this->repository->getRestaurantItems($input);

        if( $restaurantsitems->count() )
        {
            return $this->respondSuccess('Restaurant Items Found.', RestaurantItemsResource::collection($restaurantsitems));
        }

        return $this->respondWithError('Restaurant Items not found.');
    }

    /**
     * Method getItem
     *
     * @param SingleItemRequest $request [explicite description]
     *
     * @return JsonResponse
     */
    public function getItem(SingleItemRequest $request)
    {
        $data                   = $request->validated();
        $data['is_available']   = 1;
        $restaurantsitem        = $this->repository->getSingleItem($data);

        return $this->respondSuccess('Restaurant Items Found.', new RestaurantItemsResource($restaurantsitem));
    }
}
