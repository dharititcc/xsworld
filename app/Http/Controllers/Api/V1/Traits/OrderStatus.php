<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Exceptions\GeneralException;
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
        // if($order->order_category_type == 2) {
            // if($status == Order::READYFORPICKUP)
            // {
            //     OrderItem::where('order_id',$order_id)->where('category_id',$category_id)->update(['status'=> OrderItem::ACCEPTED]);
            // } else {
            //     OrderItem::where('order_id',$order_id)->where('category_id',$category_id)->update(['status' => OrderItem::COMPLETED]);
            //     $totalItemCount = OrderItem::where('order_id',$order_id)->whereNotNull('category_id')->count();
            //         // dd($orderItemCount);
            //     $totalCompletedItem = OrderItem::where('order_id',$order_id)->where('status', OrderItem::COMPLETED)->count();
            //     if($totalItemCount === $totalCompletedItem) {
            //         foreach($order->order_items as $orderitem)
            //         {
            //             if($orderitem->status == OrderItem::COMPLETED ) {
            //                 $order->update(['status'=>$status]);
            //                 $order->refresh();
            //                 $order->loadMissing(['items']);
            //             }
            //         }
            //     }
            // }

        // } else {
        //     Order::where('id',$order_id)->update(['status'=>$status]);
        // }
        $order->update(['status'=>$status]);
        $order_data = Order::find($order_id);
        // with(['order_items' => function($query) use($category_id){
        //     $query->where('category_id',$category_id);
        // },])
        // ->find($order_id);

        // dd($order_data);

        
        if($status == Order::READYFORPICKUP)
        {
            $title              = "Ready for pickup";
            $send_notification  =  $this->statusNotification($order_data,$title);
            
        } else {
            $title              = "Kitchen confirm order";
            $send_notification  = $this->statusNotification($order_data,$title);
        }

        // if($order_data->restaurant_table_id) {
        //     $devices            = $order_data->restaurant_waiter->devices->count() ? $order_data->restaurant_waiter->devices()->pluck('fcm_token')->toArray() : [];
        //     $title              = "Your order is Ready for pickup";
        //     $message            = "Your Order is #".$order_data->id." Ready for pickup";
        //     $orderid            = $order_data->id;
        //     if(!empty($devices)) {
        //         $send_notification  = sendNotification($title,$message,$devices,$orderid);
        //     }
        // }

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

    public function statusNotification(Order $order_data,$title)
    {
        // Waiter Notify
        foreach($order_data->restaurant->waiters as $waiter)
        {
            // dd($waiter->user->devices()->pluck('fcm_token')->toArray());
            if($order_data->restaurant_table_id) {
                $waiter_devices            = $waiter->user->devices->count() ? $waiter->user->devices()->pluck('fcm_token')->toArray() : [];
                // $title              = $title;
                $message            = "Your Order is #".$order_data->id." Ready for pickup";
                $orderid            = $order_data->id;
                if(!empty($waiter_devices)) {
                    return sendNotification($title,$message,$waiter_devices,$orderid);
                }
            } else {
                throw new GeneralException('Device Token not Found.');
            }
        }

        // Customer Notify
        $customer_devices   = $order_data->user->devices->count() ? $order_data->user->devices()->pluck('fcm_token')->toArray() : [];
        // $title              = $title;
        $message            = "Your Order is #".$order_data->id." Ready for pickup";
        $orderid            = $order_data->id;
        if(!empty($customer_devices)) {
            return sendNotification($title,$message,$customer_devices,$orderid);
        } else {
            throw new GeneralException('Device Token not Found.');
        }
    }
}