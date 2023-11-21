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

        if($order_data->restaurant_table_id) {
            $devices            = $order_data->restaurant_waiter->devices()->pluck('fcm_token')->toArray();
            $title              = "Your order is Ready for pickup";
            $message            = "Your Order is #".$order_data->id." Ready for pickup";
            $orderid            = $order_data->id;
            $send_notification  = sendNotification($title,$message,$devices,$orderid);
        }

        return $order_data;
    }
}