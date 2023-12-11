<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(session('restaurant'));
        if( access()->isRestaurantOwner() )
        {
            $user = access()->user();
            $user->loadMissing(['restaurants']);

            if( session('restaurant') )
            {
                $restaurant = session('restaurant');
                $restaurant->refresh();
            }
            else
            {
                $restaurantData = $user->restaurants()->first();
                $restaurant = session(['restaurant' => $restaurantData]);

                return redirect()->route('home');
            }

            $res_tables = RestaurantTable::where('restaurant_id',$restaurant->id)->get();
            $active_tbl = RestaurantTable::where(['restaurant_id' => $restaurant->id, 'status' =>RestaurantTable::ACTIVE])->count();
            $days = Day::all();
            $restaurant->loadMissing(['restaurant_time']);
            $res_times = $restaurant->restaurant_time;
            return view('restaurant.dashboard',compact('res_tables','active_tbl','days','res_times'));
        }

        // 404
        abort(404);
    }

    /**
     * Method analytics
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function analytics(Request $request)
    {
        $restaurant = session('restaurant');
        $restaurant->refresh();

        $categories = $restaurant->categories()->with(['children_parent'])->whereNotNull('parent_id')->get();
        $orders     = $restaurant->orders()->select(DB::raw("COUNT(*) as count"))->where('status',Order::COMPLETED)->where(function ($query) {
                $query->where('created_at', '>', '2023-11-28')
                    ->orWhere('created_at', '=','2023-11-30');
        })->pluck('count');
        // dd($orders);
        // echo common()->formatSql($orders);die;
        // foreach($orders as $order)
        // {
        //     $orderItems = $order->order_items()->get();
        //     foreach($orderItems as $orderItem)
        //     {

        //     }
        //     restaurant_item()->get();
        //     dd($order);
        // }
        
        return view('analytics.index', [
            'categories' => $categories,
            'orders'    => $orders,
        ]);
    }
}
