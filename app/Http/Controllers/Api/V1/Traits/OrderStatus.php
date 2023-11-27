<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait OrderStatus
{
    public function statusChange(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $auth_user = auth()->user();
        foreach($auth_user->restaurant_kitchen->restaurant->main_categories as $category)
        {
            if($category->name == "Food") {
                $category_id = $category->id;
            }
        }

        $order = Order::find($order_id);
        if($order->order_category_type == 2) {
            $order = OrderItem::where('order_id',$order_id)->where('category_id',$category_id)->update(['status'=>$status]);
        } else {
            $order = Order::where('id',$order_id)->update(['status'=>$status]);
        }
        $order_data = Order::with(['order_items' => function($query) use($category_id){
            $query->where('category_id',$category_id);
        },])->find($order_id);

        if($order_data->restaurant_table_id) {
            $devices            = $order_data->restaurant_waiter->devices()->pluck('fcm_token')->toArray();
            $title              = "Your order is Ready for pickup";
            $message            = "Your Order is #".$order_data->id." Ready for pickup";
            $orderid            = $order_data->id;
            $send_notification  = sendNotification($title,$message,$devices,$orderid);
        }

        return $order_data;
    }

    // public function foodCategory(User $user)
    // {
    //     foreach($user->restaurant_kitchen->restaurant->main_categories as $category)
    //     {
    //         if($category->name == "Food") {
    //             $category_id = $category->id;
    //         }
    //     }
    // }
}