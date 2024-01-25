<?php

namespace App\Http\Controllers;

use App\Models\CustomerTable;
use App\Models\Day;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Repositories\AnalyticRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class HomeController extends Controller
{

    /** @var \App\Repositories\AnalyticRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\AnalyticRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(AnalyticRepository $repository)
    {
        $this->middleware('auth');

        $this->repository = $repository;
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

            $key_insights   = $this->repository->getKeyInsights($restaurant->id);

            $days = Day::all();
            $restaurant->loadMissing(['restaurant_time']);
            $res_times = $restaurant->restaurant_time;
            return view('restaurant.dashboard',compact('key_insights','days','res_times'));
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


        if($request->ajax())
        {
            dd($request->get('category'));
            $items = $this->repository->getAnalyticsTableData($restaurant);

            return Datatables::of($items)
            ->make(true);
        }

        $key_insights   = $this->repository->getKeyInsights($restaurant->id);

        return view('analytics.index', [
            'categories'    => $categories,
            'restaurant'    => $restaurant,
            'key_insights'  => $key_insights
        ]);
    }

    /**
     * Method filterAnalytics
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function filterAnalytics(Request $request)
    {
        $input = $request->all();
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'country']);
        $chartData  = $this->repository->getChart($restaurant ,$input);
        // dd($chartData);
        return response()->json([
            'message' => 'Hello Test',
            'data'    => $chartData['data'],
            'x'       => $chartData['dates'],
        ]);
    }
}