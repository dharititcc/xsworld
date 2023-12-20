<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\FaqResource;
use App\Repositories\FaqRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqController extends APIController
{
    /** @var \App\Repositories\FaqRepository $repository */
    protected $repository;

     /**
     * Method __construct
     *
     * @param FaqRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(FaqRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Method index
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $faq        = $this->repository->getFaq();

        if( $faq->count() )
        {
            return $this->respondSuccess('FAQs Found.', FaqResource::collection($faq));
        }

        return $this->respondWithError('FAQs not found.');
    }
}
