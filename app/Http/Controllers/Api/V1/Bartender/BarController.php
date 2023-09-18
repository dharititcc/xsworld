<?php

namespace App\Http\Controllers\Api\V1\Bartender;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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
        $completedOrder     = $this->repository->getCompletedOrder();

        $data = [
            'incoming_order'       => $incomingOrder->count() ? OrderResource::collection($incomingOrder) : [],
            'confirmed_order'      => $confirmedOrder->count() ? OrderResource::collection($confirmedOrder) : [],
            'completed_order'      => $completedOrder->count() ? OrderResource::collection($completedOrder) : [],
        ];

        return $this->respondSuccess('Orders found.', $data);
    }

    /**
     * Method completedorderhistory
     *
     * @return void
     */
    public function completedorderhistory()
    {
        $completedOrder      = $this->repository->getCompletedOrder();

        if( $completedOrder->count() )
        {
            return $this->respondSuccess('Orders Found.', $completedOrder->count() ? OrderResource::collection($completedOrder) : [],);
        }

        throw new GeneralException('Order not found.');
    }

    /**
     * Method show
     *
     * @param Order $order [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        if( isset( $order->id ) )
        {
            return $this->respondSuccess('Order detail found.', new OrderResource($order));
        }

        throw new GeneralException('Order not found.');
    }

    /**
     * Method orderUpdate
     *
     * @param OrderUpdateRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderUpdate(OrderUpdateRequest $request)
    {
        $order_data   = $this->repository->updateOrder($request->validated());

        if($order_data->id)
        {
            return $this->respondSuccess('Order data updated', new OrderResource($order_data));
        }

        throw new GeneralException('Order not found.');
    }
}
