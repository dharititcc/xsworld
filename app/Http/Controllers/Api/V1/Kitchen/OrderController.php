<?php

namespace App\Http\Controllers\Api\V1\Kitchen;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Traits\OrderStatus;
use App\Http\Resources\KitchenOrderListingResource;
use App\Http\Resources\KitchenOrderListResource;
use App\Http\Resources\KitchenOrderResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
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

        // restaurant close time
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

        $orderList    = $this->repository->getKitchenConfirmedOrders();
        $barOrderList = $this->repository->getKitchenOrderCollections();
        // if( $orderList->count() ||  $barOrderList->count())
        // {
            $data = [
                'confirmOrder'       => $orderList->count() ? KitchenOrderResource::collection($orderList) : [],
                'CompletedOrders'      => $barOrderList->count() ? KitchenOrderListingResource::collection($barOrderList) : [],
                'restaurant_close_time'     => isset($close_time) ? $close_time  : "00:00:00",
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
        return $this->respondSuccess('Order Status Changed successfully.', new KitchenOrderResource($orderChange));
    }

    /**
     * Method orderHistory
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderHistory(Request $request)
    {
        $orderList = $this->repository->getCompletedKitchenOrders($request->all());

        $total = $orderList->count();
        $orderList = $orderList->get();
        $data = [
            'total_orders'   => $total,
            'orders'         => KitchenOrderListResource::collection($orderList),
        ];

        if( $orderList->count() )
        {
            return $this->respondSuccess('Order History Fetched successfully.', $data);
        }

        throw new GeneralException('There is no order found');
    }

    public function orderDetail(Request $request)
    {
        $order = $request->order_id;
        $orderShow = $this->repository->getOrderById($order);
        if($orderShow)
        {
            return $this->respondSuccess('Order Details Fetched successfully.', new KitchenOrderResource($orderShow));
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
