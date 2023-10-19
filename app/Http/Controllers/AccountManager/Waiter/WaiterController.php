<?php

namespace App\Http\Controllers\AccountManager\Waiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWaiterRequest;
use App\Http\Resources\UserResource;
use App\Models\RestaurantPickupPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class WaiterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = session('restaurant');

        $restaurant->loadMissing(['kitchens']);
        $waiters = User::select('id','first_name','username','email')->where('user_type',User::WAITER)->get();
        $barpickzones = User::select('id','first_name','username','email')->where('user_type',User::BARTENDER)->get();
        // $kitchens = User::select('id','first_name','username','email')->where('user_type',User::KITCHEN)->get();
        $pickup_points = RestaurantPickupPoint::restaurantget($restaurant->id)->whereNull('user_id')->get();

        return view('accountManager.waiter.index',[
            'waiters' => $waiters,
            'pickup_points' => $pickup_points,
            'barpickzones' => $barpickzones,
            // 'kitchen_pickpoints' => $kitchen_pickpoints,
            'kitchens' => $restaurant->kitchens,
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
    public function store(UpdateWaiterRequest $request)
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
        // dd($request->waiter_id);
        // $usernameUnique = User::select('username')->where('username',$request->waiter_id)->pluck('username');
        
        $waiterArr = User::create([
            'username' => $request->waiter_id,
            'first_name' => $request->first_name,
            'password' => Hash::make($request->password),
            'email' => $email,
            'country_code' => $countryCode,
            'phone' => $mobileNumber,
            'user_type' => User::WAITER,
        ]);
        
        return $waiterArr->refresh();
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
        // dd($user);
        $user = User::find($user);
        return $user->toArray();
        // return new UserResource($user);
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
    public function update(Request $request, User $waiter)
    {
        $dataArr = [
            'first_name' => $request->first_name,
            'password' => Hash::make($request->password),
        ];
        $waiter->update($dataArr);
        $waiter->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $waiterDel = User::find($id);
        $waiterDel->delete();
        return redirect()->back();
    }
}
