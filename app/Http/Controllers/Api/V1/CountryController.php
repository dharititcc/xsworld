<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;

class CountryController extends APIController
{
    /** @var \App\Repositories\CountryRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param CountryRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(CountryRepository $repository)
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
     * @OA\Get(
     ** path="/api/v1/countries/get-countries",
     *   tags={"Country"},
     *   summary="Get Countries",
     *
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
    public function index()
    {
        $countries        = $this->repository->getCountries();

        if( $countries->count() )
        {
            return $this->respondSuccess('Countries Found.', CountryResource::collection($countries));
        }

        return $this->respondWithError('Countries not found.');
    }
}
