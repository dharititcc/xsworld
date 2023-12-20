<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user       = auth()->user();
        $restaurant = session('restaurant')->loadMissing(['country']);
        $orders     = Order::with([
            'order_items' => [
                'restaurant_item',
                'addons',
                'mixer'
            ]
        ])
        ->where('restaurant_id', $restaurant->id)
        ->where('type', Order::ORDER)
        ->orderBy('id','desc')
        ->paginate(15);

        return view('order.order-history', compact('orders', 'restaurant'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $restaurant = session('restaurant')->loadMissing(['country']);
        $html = view('order.partials.order-detail', ['order' => $order, 'restaurant' => $restaurant])->render();

        return response()->json([
            'status'    => true,
            'message'   => 'Details found.',
            'data'      => $html,
            'order'     => $order
        ], 200);
    }
}
