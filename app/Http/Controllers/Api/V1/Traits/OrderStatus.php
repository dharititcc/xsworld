<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait OrderStatus
{
    public function statusChange(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $auth_user = auth()->user();

        $order = Order::where('id',$order_id)->update(['status'=>$status]);
        $order_data = Order::find($order_id);

        return $order_data;
    }
}