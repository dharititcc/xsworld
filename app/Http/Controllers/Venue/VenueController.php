<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Models\Day;
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
        $days = Day::all();
        $restaurant->loadMissing(['restaurant_time']);
        $res_times = $restaurant->restaurant_time;

        return view('venue.index',compact('restaurant','days','res_times'));
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
