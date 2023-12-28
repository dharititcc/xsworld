<?php

namespace App\Http\Controllers\Api\V1\Bartender;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\OrderHistoryRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\BarOrderDetailResource;
use App\Http\Resources\BarOrderListingResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function barOrderHistory()
    {
        $incomingOrder      = $this->repository->getIncomingOrder();
        $confirmedOrder     = $this->repository->getConfirmedOrder();
        $completedOrder     = $this->repository->getBarCollections();

        $data = [
            'incoming_order'       => $incomingOrder->count() ? BarOrderListingResource::collection($incomingOrder) : [],
            'confirmed_order'      => $confirmedOrder->count() ? BarOrderListingResource::collection($confirmedOrder) : [],
            'completed_order'      => $completedOrder->count() ? BarOrderListingResource::collection($completedOrder) : [],
        ];

        return $this->respondSuccess('Orders found.', $data);
    }


    public function incomingOrder()
    {
        $incomingOrder      = $this->repository->getIncomingOrder();
        $data = [
            'incoming_order'       => $incomingOrder->count() ? BarOrderListingResource::collection($incomingOrder) : [],
        ];

        return $this->respondSuccess('Incoming Orders found.', $data);
    }


    public function confirmOrder()
    {
        $confirmedOrder     = $this->repository->getConfirmedOrder();
        $data = [
            'confirmed_order'      => $confirmedOrder->count() ? BarOrderListingResource::collection($confirmedOrder) : [],
        ];

        return $this->respondSuccess('Confirmed Orders found.', $data);
    }


    public function completedOrder()
    {
        $completedOrder     = $this->repository->getBarCollections();
        $data = [
            'completed_order'      => $completedOrder->count() ? BarOrderListingResource::collection($completedOrder) : [],
        ];
        return $this->respondSuccess('Completed Orders found.', $data);
    }

    /**
     * Method completedorderhistory
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    public function completedorderhistory(OrderHistoryRequest $request)
    {
        $completedOrder      = $this->repository->getCompletedOrder($request->validated());

        if( $completedOrder['orders']->count() )
        {
            $historyArray = [
                'total_orders'  => $completedOrder['total_orders'],
                'orders'        => OrderListResource::collection($completedOrder['orders'])
            ];

            return $this->respondSuccess('Orders Found.', $historyArray);
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
            return $this->respondSuccess('Order detail found.', new BarOrderDetailResource($order));
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
    public function orderStatusUpdate(OrderUpdateRequest $request)
    {
        $order_data   = $this->repository->updateStatusOrder($request->validated());

        if($order_data->id)
        {
            return $this->respondSuccess('Order data updated');
        }

        throw new GeneralException('Order not found.');
    }

    /**
     * Method gostatus
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function gostatus(Request $request)
    {
        $input          = $request->all();
        $pickup_point   = $this->repository->updateStatus($input);

        $data = [
            'code'  => $input['status'],
        ];
        return $this->respondSuccess('Status updated',$data);
    }
}
