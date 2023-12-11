<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

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
            $restaurant = session('restaurant');
            $restaurant->refresh();
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
        // $orders     = $restaurant->orders()->select(DB::raw("COUNT(*) as count"))->where('status',Order::COMPLETED)->where(function ($query) {
        //         $query->where('created_at', '>', '2023-11-28')
        //             ->orWhere('created_at', '=','2023-11-30');
        // })->pluck('count');

        $order     = $restaurant->orders()->select(DB::raw("SUM(total) as total_orders_rs, DATE(created_at) as day"))
                    ->where('status',Order::CONFIRM_PICKUP)
                    ->where(function($query)
                    {
                        $query->where('created_at', '>=', '2023-11-28')
                            ->orWhere('created_at', '<=','2023-11-30');
                    })
                    ->groupBy(DB::raw("DATE(created_at)"))
                    ->get();


            // $orders     = $restaurant->orders()
            //         ->with(['order_items','order_items.restaurant_item'])
            //         ->where('status',Order::CONFIRM_PICKUP)
            //         ->where(function($query)
            //         {
            //             $query->where('created_at', '>=', '2023-11-28')
            //                 ->orWhere('created_at', '<=','2023-11-30');
            //         })
            //         ->groupBy(DB::raw("DATE(created_at)"));

            // $data = $orders->orderByDesc('id')->get();
            // dd($data);
        if($request->ajax())
        {
            $orders     = $restaurant->orders()
                    ->with(['order_items','order_items.restaurant_item'])
                    ->where('status',Order::CONFIRM_PICKUP)
                    ->where(function($query)
                    {
                        $query->where('created_at', '>=', '2023-11-28')
                            ->orWhere('created_at', '<=','2023-11-30');
                    })
                    ->groupBy(DB::raw("DATE(created_at)"));

            $data = $orders->orderByDesc('id')->get();
            return Datatables::of($data)
                ->make(true);
                // dd($orders->order_items);
        }
        // echo common()->formatSql($order);die;
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
            // 'orders'    => $orders,
            'order'     => $order,
        ]);
    }
}
