<?php

namespace App\Http\Controllers\Admin\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantRequest;
use App\Models\Currency;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\RestaurantOwnerSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.restaurant.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestaurantRequest $request)
    {

        $currency_id = Currency::select('id')->where('id',$request->country_id)->first();
        $addressInfo    = [
            'name'          => $request->name,
            'street1'       => $request->street1,
            'street2'       => $request->street2,
            'currency_id'   => $currency_id->id,
            'country_id'    => (int)$request->country_id,
            'state'         => $request->state,
            'city'          => $request->city,
            'postcode'      => $request->postcode,
            'specialisation'=> $request->description,
        ];
        $restaurant = Restaurant::create($addressInfo);
        $restaurant->refresh();
        if ($request->hasFile('image'))
        {
            $this->upload($request->file('image'), $restaurant);
        }
        $ownerInfo      = [
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'user_type'     => User::RESTAURANT_OWNER,
        ];
        $user = User::create($ownerInfo);
        $restaurant->owners()->attach($user->id);
        return $restaurant->refresh();
    }


    /**
     * Method upload
     *
     * @param $file $file [explicite description]
     * @param \App\Models\Category $model [explicite description]
     *
     * @return void
     */
    private function upload($file, $model)
    {
        //Move Uploaded File
        $destinationPath = public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'restaurant');
        $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
        $file->move($destinationPath, $profileImage);

        $model->attachment()->delete();

        $model->attachment()->create([
            'stored_name'   => $profileImage,
            'original_name' => $profileImage
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('Hii');
    }
}
