<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

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
}
