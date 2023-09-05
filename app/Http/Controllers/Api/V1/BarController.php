<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repositories\BarRepository;
use Illuminate\Http\Request;

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
     * @return void
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
