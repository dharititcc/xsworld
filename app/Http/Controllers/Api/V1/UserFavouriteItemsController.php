<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserFavouriteItemsRequest;
use App\Repositories\UserRepository;

class UserFavouriteItemsController extends APIController
{
    /** @var \App\Repositories\UserRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\UserRepository $repository [explicite description]
     *
     * @return void
    */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }

    /**
     * Method favourite and unfavourite items
     *
     * @param \App\Http\Requests\UserFavouriteItemsRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\Post(
     ** path="/api/v1/users/favourite",
     *   tags={"Favourite and unfavourite items"},
     *   summary="favourite-and-unfavourite-items",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *     @OA\Parameter(
     *      name="restaurant_item_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     * ),
     *    @OA\Response(
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
     *)
     **/
    public function favorite(UserFavouriteItemsRequest $request)
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $data = $request->validated();

        $this->repository->addFavourite($data, $user);

        $count = $this->repository->checkFavouriteItemExist($data['restaurant_item_id'], $user);

        if( $count > 0 )
        {
            return $this->respond([
                'status' => true,
                'message'=> 'Favourite Items added successfully.'
            ]);
        }
        else
        {
            return $this->respond([
                'status' => true,
                'message'=> 'Favourite Items removed successfully.'
            ]);
        }
    }
}
