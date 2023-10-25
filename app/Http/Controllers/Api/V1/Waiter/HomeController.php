<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends APIController
{
    public function activeTable()
    {
        $auth_waiter = auth('api')->user();
        $orderTbl = Order::where('waiter_id',$auth_waiter->id)->where('type',Order::ORDER)->get();
        return $this->respondSuccess('Order Fetched successfully.', OrderResource::collection($orderTbl));
    }
}
