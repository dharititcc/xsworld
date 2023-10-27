<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use App\Models\RestaurantTime;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = session('restaurant');
        $restaurant->refresh();
        $restaurant->loadMissing(['orders']);
        // $order_reviews = $restaurant->orders->where('status', Order::COMPLETED);
        $order_reviews = $restaurant->orders()->with(['reviews'])->whereHas('reviews')->get();
        // dd($order_reviews);
        $days = Day::all();
        $restaurant->loadMissing(['restaurant_time']);
        $res_times = $restaurant->restaurant_time;
        // $order_reviews = OrderReview::all();

        return view('venue.index', compact('restaurant','days','res_times','order_reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = session('restaurant');
        $validator = Validator::make($request->all(), [
            'end_time' => 'required_with:start_time.*|array',
            'end_time.*' => 'required_with:start_time.*',
        ]);
        foreach($request->start_time as $key => $time)
        {
            $start  = $time;
            $end    = $request->end_time[$key];
            $day_id = $key;
            $res_time = RestaurantTime::updateOrCreate([
                'restaurant_id'     => $restaurant->id,
                'days_id'           => $day_id],
                ['start_time'        => $start,
                'close_time'        => $end,
            ]);
        }
        return $res_time->refresh();
    }

    private function upload($file, Restaurant $restaurants)
    {
        //Move Uploaded File
        $destinationPath = public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'restaurants');
        $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
        $file->move($destinationPath, $profileImage);

        $restaurants->attachment()->delete();

        $restaurants->attachment()->create([
            'stored_name'   => $profileImage,
            'original_name' => $profileImage
        ]);
    }

    public function imageUpload(Request $request)
    {
        $restaurant = session('restaurant');
        // dd($request->all());
        
        if ($request->hasFile('image'))
        {
            $this->upload($request->file('image'), $restaurant);
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
