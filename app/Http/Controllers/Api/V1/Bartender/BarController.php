<?php

namespace App\Http\Controllers\Api\V1\Bartender;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\OrderResource;
use App\Repositories\BarRepository;

class BarController extends APIController
{
    /** @var \App\Repositories\BarRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param BarRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(BarRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method barOrderHistory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function barOrderHistory()
    {
        $incomingOrder      = $this->repository->getIncomingOrder();
        $confirmedOrder     = $this->repository->getConfirmedOrder();

        $data = [
            'incoming_order'       => $incomingOrder->count() ? OrderResource::collection($incomingOrder) : [],
            'confirmed_order'      => $confirmedOrder->count() ? OrderResource::collection($confirmedOrder) : []
        ];

        return $this->respondSuccess('Orders found.', $data);
    }
}
