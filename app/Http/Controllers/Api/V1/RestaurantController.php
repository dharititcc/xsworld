<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\RestaurantFeaturedRequest;
use App\Http\Requests\RestaurantFilterApiRequest;
use App\Http\Requests\RestaurantItemSearchRequest;
use App\Http\Requests\RestaurantSubCategoryReuest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\RestaurantItemsResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
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
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="longitude",
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
    public function index(RestaurantFilterApiRequest $request)
    {
        $data               = $request->validated();
        $data['type']       = Restaurant::RESTAURANT;
        $restaurants        = $this->repository->getRestaurants($data);

        if( $restaurants->count() )
        {
            return $this->respondSuccess('Restaurant Found.', RestaurantResource::collection($restaurants));
        }

        return $this->respondWithError('Restaurant not found.');
    }

    /**
     * Method events
     *
     * @param RestaurantFilterApiRequest $request [explicite description]
     *
     * @return void
     */
    public function events(RestaurantFilterApiRequest $request)
    {
        $data               = $request->validated();
        $data['type']       = Restaurant::EVENT;
        $events             = $this->repository->getRestaurants($data);

        if( $events->count() )
        {
            return $this->respondSuccess('Events Found.', RestaurantResource::collection($events));
        }

        return $this->respondWithError('Events not found.');
    }

    /**
     * Method index
     *
     * @param \App\Http\Requests\RestaurantFeaturedRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     ** path="/api/v1/restaurants/featured",
     *   tags={"Restaurants"},
     *   summary="Get Restaurants Featured and user favourite items",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *
     *  @OA\Parameter(
     *      name="restaurant_id",
     *      in="query",
     *      required=false,
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
    public function featured(RestaurantFeaturedRequest $request)
    {
        $featuredItems      = $this->repository->getFeaturedItems($request->validated());
        $userFavouriteItems = $this->repository->getuserFavouriteItems($request->validated());

        $data = [
            'user_favourite_items'   => $userFavouriteItems->count() ? RestaurantItemsResource::collection($userFavouriteItems) : [],
            'featured_items'         => $featuredItems->count() ? RestaurantItemsResource::collection($featuredItems) : []
        ];

        return $this->respondSuccess('Restaurant featured items found.', $data);
    }

    /**
     * Method index
     *
     * @param \App\Http\Requests\RestaurantSubCategoryReuest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     ** path="/api/v1/restaurants/sub-categories",
     *   tags={"Restaurants"},
     *   summary="Get Sub categories by Restaurant and Category.",
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
     *      required=false,
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
    public function subCategories(RestaurantSubCategoryReuest $request)
    {
        $restaurants        = $this->repository->getRestaurantSubCategories($request->validated());

        if( $restaurants->count() )
        {
            return $this->respondSuccess('Sub Categories Found.', CategoryResource::collection($restaurants));
        }

        return $this->respondWithError('Sub Categories not found.');
    }

    /**
     * Method itemSearchByName
     *
     *  @return \Illuminate\Http\JsonResponse
     *
     * @return void
     */
    public function itemSearchByName(RestaurantItemSearchRequest $request)
    {
        $restaurantsItems        = $this->repository->getItembyName($request->validated());

        if( $restaurantsItems->count() )
        {
            return $this->respondSuccess('Items Found.', RestaurantItemsResource::collection($restaurantsItems));
        }

        return $this->respondWithError('Items not found.');
    }
}
