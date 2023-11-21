<?php

namespace App\Http\Controllers\Api\V1\Kitchen;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Traits\OrderStatus;
use App\Http\Resources\BarOrderListingResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Models\KitchenPickPoint;
use App\Models\RestaurantKitchen;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        if($auth_kitchen->restaurant_kitchen->restaurant->restaurant_time) {
            foreach($auth_kitchen->restaurant_kitchen->restaurant->restaurant_time as $res_time)
            {
                $date = Carbon::now();
                $day_num = $date->toArray();
                // $day_num = $date->toRfc850String();
                // dd($day_num['dayOfWeek']);
                if($res_time->day->id == $day_num['dayOfWeek']) {
                    $close_time = $res_time->close_time;
                }
            }
        }

        $kitchen_orders = RestaurantKitchen::where('user_id',$auth_kitchen->id)->select('restaurant_id')->get()->toArray();
        // $kitchen_pickup_points = KitchenPickPoint::where('user_id',$auth_kitchen->id)->select('pickup_point_id')->get()->toArray();

        $orderList = $this->repository->GetKitchenOrders($kitchen_orders);
        $barOrderList = $this->repository->getBarCollections($kitchen_orders);
        // if( $orderList->count() ||  $barOrderList->count())
        // {
            $data = [
                'confirmOrder'       => $orderList->count() ? OrderResource::collection($orderList) : [],
                'CompletedOrders'      => $barOrderList->count() ? BarOrderListingResource::collection($barOrderList) : [],
                'restaurant_close_time'     => $close_time,
            ];
            return $this->respondSuccess('Order Fetched successfully.', $data);
        // }

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
        $kitchen_pickup_points = RestaurantKitchen::where('user_id',$auth_kitchen->id)->select('restaurant_id')->get()->toArray();
        $orderList = $this->repository->GetKitchenOrders($kitchen_pickup_points,1);
        if( $orderList->count() )
        {
            return $this->respondSuccess('Order History Fetched successfully.', OrderListResource::collection($orderList));
        }

        throw new GeneralException('There is no order found');
    }

    public function orderDetail(Request $request)
    {
        $order = $request->order_id;
        $orderShow = $this->repository->getOrderById($order);
        if($orderShow)
        {
            return $this->respondSuccess('Order Details Fetched successfully.', new OrderResource($orderShow));
        }
        throw new GeneralException('There is no order found');
    }


    public function gostatus(Request $request)
    {
        $input          = $request->all();
        $restaurant_kitchen   = $this->repository->updateStatus($input);

        return $this->respondSuccess('Status updated');
    }

    public function callWaiter()
    {
        $manullyCallWaiter = $this->repository->callWaiterNotify();
        return $this->respondSuccess('Notification Send Successfully');
    }
}
