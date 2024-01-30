<?php

namespace App\Http\Controllers\Orders;

use App\Billing\Stripe;
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
        ->whereNotIn('status', [Order::PENDNIG, Order::ACCEPTED, Order::COMPLETED, Order::DELAY_ORDER])
        ->orderBy('id','desc')
        ->paginate(15);

        return view('order.order-history', compact('orders', 'restaurant'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $stripe     = new Stripe();
        $restaurant = session('restaurant')->loadMissing(['country']);
        $card       = isset( $order->amount ) && $order->amount > 0 ? $order->card_details : null;
        $html       = view('order.partials.order-detail', ['order' => $order, 'restaurant' => $restaurant, 'card' => $card])->render();

        return response()->json([
            'status'    => true,
            'message'   => 'Details found.',
            'data'      => $html,
            'order'     => $order
        ], 200);
    }
}
