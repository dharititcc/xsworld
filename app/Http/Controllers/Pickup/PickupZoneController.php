<?php

namespace App\Http\Controllers\Pickup;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantPickupPointResource;
use App\Models\RestaurantPickupPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickupZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = session('restaurant');
        // dd($restaurant);
        $pickup_points = RestaurantPickupPoint::restaurantget($restaurant->id);
        $food_pickup_points = $pickup_points->type(1)->get();
        $drink_pickup_points = $pickup_points->type(2)->get();
        return view('pickup.index',[
            'food_pickup_points' => $food_pickup_points,
            'drink_pickup_points' => $drink_pickup_points,

        ]);
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
        $request->validate([
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $restaurant = session('restaurant');
        if($request->hasFile('photo'))
        {
            $destinationPath = public_path('/storage/pickup_point');
            $pickupimage = date('YmdHis') . "." . $request->photo->getClientOriginalExtension();
            $request->photo->move($destinationPath,$pickupimage);
        }
        $pickupArr = [
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'restaurant_id' => $restaurant->id,
            'type' => $request->types,
        ];
        
        $newPickup = RestaurantPickupPoint::create($pickupArr);
        $newPickup->attachment()->create([
            'stored_name' => $pickupimage,
            'original_name' => $pickupimage
        ]);
        return $newPickup->refresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantPickupPoint $pickup)
    {
        return new RestaurantPickupPointResource($pickup);
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
    public function update(Request $request, RestaurantPickupPoint $pickup)
    {
        if(isset($pickup->id)) {
            $dataArr = [
                'name' => $request->name,
            ];
            $pickup->update($dataArr);
            $pickup->refresh();
            $pickupimage = '';
            if ($request->hasFile('photo'))
            {
                $image = $request->file('photo');
                $destinationPath = public_path('/storage/pickup_point');
                $pickupimage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $pickupimage);
                $pickup->attachment()->update([
                    'stored_name'   => $pickupimage,
                    'original_name' => $pickupimage
                ]);
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = RestaurantPickupPoint::find($id);
        $delete->delete();
        return response()->json(['success'=>'pickup zone deleted successfully.']);
    }
}
