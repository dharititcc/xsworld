<?php

namespace App\Http\Controllers\Api\V1\Kitchen;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Traits\OrderStatus;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Models\KitchenPickPoint;
use App\Models\RestaurantKitchen;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends APIController
{
    use OrderStatus;
    /** @var \App\Repositories\OrderRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\OrderRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method orderList
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderList()
    {
        $auth_kitchen = auth('api')->user();
        $kitchen_orders = RestaurantKitchen::where('user_id',$auth_kitchen->id)->select('restaurant_id')->get()->toArray();
        // dd($kitchen_orders);
        // $kitchen_pickup_points = KitchenPickPoint::where('user_id',$auth_kitchen->id)->select('pickup_point_id')->get()->toArray();
        // dd($kitchen_pickup_points);

        $orderList = $this->repository->GetKitchenOrders($kitchen_orders);
        if( $orderList->count() )
        {
            return $this->respondSuccess('Order Fetched successfully.', OrderResource::collection($orderList));
        }

        throw new GeneralException('There is no order found');
    }

    /**
     * Method updateOrderStauts
     *
     * @param \Illuminate\Http\Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStauts(Request $request)
    {
        $orderChange = $this->statusChange($request);
        return $this->respondSuccess('Order Status Changed successfully.', new OrderResource($orderChange));
    }

    /**
     * Method orderHistory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderHistory()
    {
        $auth_kitchen = auth('api')->user();
        $kitchen_pickup_points = KitchenPickPoint::where('user_id',$auth_kitchen->id)->select('pickup_point_id')->get()->toArray();
        $orderList = $this->repository->GetKitchenOrders($kitchen_pickup_points,1);
        if( $orderList->count() )
        {
            return $this->respondSuccess('Order History Fetched successfully.', OrderListResource::collection($orderList));
        }

        throw new GeneralException('There is no order found');
    }
}
