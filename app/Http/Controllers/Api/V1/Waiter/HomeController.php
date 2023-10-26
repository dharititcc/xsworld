<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class HomeController extends APIController
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function activeTable()
    {
        $auth_waiter = auth('api')->user();
        $orderTbl = Order::where('waiter_id',$auth_waiter->id)->where('type',Order::ORDER)->get();
        $kitchen_status = Order::where('type',Order::ORDER)->whereIn('status',[Order::KITCHEN_CONFIRM,Order::READYFORPICKUP])->get();
        $data = [
            'active_tables' => $orderTbl->count() ? OrderResource::collection($orderTbl) : [],
            'kitchen_status' => $kitchen_status->count() ? OrderResource::collection($kitchen_status) : [],
        ];
        return $this->respondSuccess('Waiter Order Fetched successfully.', $data);
    }

    public function gostatus(Request $request)
    {
        $input          = $request->all();
        $restaurant_waiter   = $this->repository->updateStatus($input,1);

        return $this->respondSuccess('Status updated');
    }
}
