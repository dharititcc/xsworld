<?php

namespace App\Http\Controllers\AccountManager\Bar;

use App\Http\Controllers\Controller;
use App\Models\RestaurantPickupPoint;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BarPickZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = session('restaurant');
        $pickup_points = RestaurantPickupPoint::restaurantget($restaurant->id)->whereNull('user_id')->get();
        return view('accountManager.waiter.index',[
            'pickup_points' => $pickup_points,
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
        $restaurant = session('restaurant');
        //Random Email
        $domains = ["gmail.com", "yahoo.com", "hotmail.com", "example.com", "yourdomain.com"];
        $username = $this->generateRandomString(8);
        $domains = $domains[array_rand($domains)];
        $email = $username . '@' . $domains;

        //Random Mobile number
        $countryCodes = ["+1",  "+61", "+91", "+86"];
        $countryCode = $countryCodes[array_rand($countryCodes)];
        // Generate an 8-digit random number
        $mobileNumber = mt_rand(10000000, 99999999);
        $barArr = User::create([
            'username' => $request->barpick_id,
            'first_name' => Str::random(5),
            'password' => Hash::make($request->password),
            'email' => $email,
            'country_code' => $countryCode,
            'phone' => $mobileNumber,
            'user_type' => User::BARTENDER,
        ]);
        RestaurantPickupPoint::where('id',$request->pickup_points)->update(['user_id'=>$barArr->id]);
        
        return $barArr->refresh();
    }

    
    function generateRandomString($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::find($user);
        $user->pickup_point_name = $user->pickup_point->name;
        $user->pickup_point = $user->pickup_point;
        // dd($user->toArray());
        return $user->toArray();
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
    public function update(Request $request, User $bar)
    {
        $dataArr = [
            'password' => Hash::make($request->password),
        ];
        RestaurantPickupPoint::where('id',$request->pickup_points)->update(['user_id'=>$bar->id]);
        $bar->update($dataArr);
        $bar->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barpickDel = User::find($id);
        RestaurantPickupPoint::where('user_id',$id)->update(['user_id' => null]);
        $barpickDel->delete();
        return redirect()->back();
    }
}
