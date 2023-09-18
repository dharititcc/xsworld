<?php

namespace App\Http\Controllers\AccountManager\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\RestaurantPickupPoint;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request->all());
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
        $kitchenArr = User::create([
            'username' => $request->kitchen_id,
            'first_name' => Str::random(5),
            'password' => Hash::make($request->password),
            'email' => $email,
            'country_code' => $countryCode,
            'phone' => $mobileNumber,
            'user_type' => User::KITCHEN,
        ]);
       
        $kitchen_points[] = explode(',',$request->kitchen_point);
      
        RestaurantPickupPoint::whereIn('id',$kitchen_points[0])->update(['user_id'=>$kitchenArr->id]);
        
        return $kitchenArr->refresh();
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
    public function show($id)
    {
        //
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
        $kitchenDel = User::find($id);
        RestaurantPickupPoint::where('user_id',$id)->update(['user_id' => null]);
        $kitchenDel->delete();
        return redirect()->back();
    }
}
