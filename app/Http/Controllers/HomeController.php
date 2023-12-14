<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
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
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'country']);
        $categories = $restaurant->categories()->with(['children_parent'])->whereNotNull('parent_id')->get();
        $order      = $restaurant->orders()->select(DB::raw("SUM(total) as total_orders_rs, DATE(created_at) as day"))
                    ->where('status',Order::CONFIRM_PICKUP)
                    ->where(function($query)
                    {
                        $query->where('created_at', '>=', '2023-11-28')
                            ->orWhere('created_at', '<=','2023-11-30');
                    })
                    ->groupBy(DB::raw("DATE(created_at)"))
                    ->get();

        if($request->ajax())
        {
            $items = OrderItem::select([
                'order_items.*',
                'restaurant_item_variations.name AS variation_name',
                DB::raw("COUNT(variation_id) AS variation_count"),
                DB::raw("SUM(quantity) AS variation_qty_sum"),
                'tmp.total_item',
                'tmp.total_quantity'
            ])
            ->with(['order', 'variation', 'order.restaurant', 'order.restaurant.country', 'restaurant_item', 'restaurant_item'])
            ->leftJoin(DB::raw("
                (
                    SELECT
                        order_items.id AS `order_item_id`,
                        COUNT(order_items.id) AS total_item,
                        SUM(order_items.quantity) AS `total_quantity`
                    FROM order_items
                    LEFT JOIN orders ON orders.id = order_items.order_id
                    WHERE variation_id IS NULL
                    AND orders.restaurant_id = {$restaurant->id}
                    GROUP BY restaurant_item_id, variation_id
                ) AS `tmp`
            "), function($join)
            {
                $join->on('order_items.id', '=', 'tmp.order_item_id');
            })
            ->leftJoin('restaurant_item_variations', 'restaurant_item_variations.id', '=', 'order_items.variation_id')
            ->whereHas('order', function($query) use($restaurant){
                $query->where('restaurant_id', $restaurant->id);
                $query->where('status', Order::CONFIRM_PICKUP);
            })
            ->item()
            ->where(function($query)
            {
                $query->whereRaw("DATE(`order_items`.`created_at`) BETWEEN '2023-11-28' AND '2023-12-13'");
            })
            ->groupBy(['order_items.restaurant_item_id', 'order_items.variation_id'])
            // echo common()->formatSql($items);die;
            ->get();

            return Datatables::of($items)
            ->make(true);
        }

        return view('analytics.index', [
            'categories'    => $categories,
            'restaurant'    => $restaurant,
            'order'         => $order,
        ]);
    }
}