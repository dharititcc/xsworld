<?php

namespace App\Http\Controllers\Admin\Restaurant;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantRequest;
use App\Http\Requests\RestaurantUpdateRequest;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Restaurant;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $currency_id    = Currency::select('id')->where('id',$request->country_id)->first();
        $country_name   = Country::select('name')->where('id',$request->country_id)->first();
        $address = [];
        if(!isset($request->latitude) && !isset($request->longitude))
        {
            $address = addressLatLong($request->street1 . $request->city . $request->state);
            if($country_name->name != $address['country'])
            {
                throw new GeneralException('Please Select Proper Country');
            }
        }

        $addressInfo    = [
            'name'          => $request->name,
            'phone'         => $request->phone,
            'street1'       => $request->street1,
            'street2'       => $request->street2,
            'currency_id'   => $currency_id->id,
            'country_id'    => (int)$request->country_id,
            'state'         => $request->state,
            'city'          => $request->city,
            'postcode'      => $request->postcode,
            'latitude'      => isset($request->latitude) ? $request->latitude : $address['latitude'],
            'longitude'     => isset($request->longitude) ? $request->longitude : $address['longitude'],
            'type'          => $request->type,
            'start_date'    => isset($request->start_date) ? $request->start_date : null,
            'end_date'      => isset($request->end_date) ? $request->end_date : null,
            'specialisation'=> $request->description,
        ];

        try
        {
            DB::beginTransaction();
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

            DB::commit();
            return $restaurant->refresh();
        }
        catch(Exception $e)
        {
            DB::rollBack();
            throw new GeneralException('Something went wrong. Please try again later.');
        }
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
        $destinationPath = public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'restaurants');
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
    public function show(Restaurant $restaurant)
    {
        $restaurant->loadMissing(['owners','attachment']);
        $restaurant = $restaurant->toArray();
        $restaurant = [
            'name'              => $restaurant['name'],
            'street1'           => $restaurant['street1'],
            'street2'           => $restaurant['street2'],
            'city'              => $restaurant['city'],
            'state'             => $restaurant['state'],
            'type'              => $restaurant['type'],
            'country_id'        => $restaurant['country_id'],
            'latitude'          => $restaurant['latitude'],
            'longitude'         => $restaurant['longitude'],
            'image'             => $restaurant['attachment'] ? asset('storage/restaurants/'.$restaurant['attachment']['stored_name']) : '',
            'postcode'          => $restaurant['postcode'],
            'start_date'        => isset($restaurant['start_date']) ? $restaurant['start_date'] : '',
            'end_date'          => isset($restaurant['end_date']) ? $restaurant['end_date'] : '',
            'specialisation'    => $restaurant['specialisation'],
            'id'                => $restaurant['owners'][0]['id'],
            'first_name'        => $restaurant['owners'][0]['first_name'],
            'last_name'         => $restaurant['owners'][0]['last_name'],
            'email'             => $restaurant['owners'][0]['email'],
            'phone'             => $restaurant['owners'][0]['phone'],
            'password'          => $restaurant['owners'][0]['password'],
        ];
        return response()->json([
            'status' => true,
            'data'   => $restaurant
        ]);
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
    public function update(RestaurantUpdateRequest $request, Restaurant $restaurant)
    {
        $currency_id = Currency::select('id')->where('id', $request->country_id)->first();
        $address = [];
        if(!isset($request->latitude) && !isset($request->longitude))
        {
            $address = addressLatLong($request->street1 .  $request->city . $request->state);
            $country_name = Country::select('name')->where('id',$request->country_id)->first();
            if($country_name->name != $address['country'])
            {
                throw new GeneralException('Please Select Proper Country');
            }
        }

        $addressInfo    = [
            'name'          => $request->name,
            'street1'       => $request->street1,
            'street2'       => $request->street2,
            'currency_id'   => $currency_id->id,
            'country_id'    => (int)$request->country_id,
            'state'         => $request->state,
            'phone'         => $request->phone,
            'city'          => $request->city,
            'latitude'      => isset($request->latitude) ? $request->latitude : $address['latitude'],
            'longitude'     => isset($request->longitude) ? $request->longitude : $address['longitude'],
            'postcode'      => $request->postcode,
            'start_date'    => isset($request->start_date) ? $request->start_date : '',
            'end_date'      => isset($request->end_date) ? $request->end_date : '',
            'specialisation'=> $request->description,
        ];
        // dd($restaurant);
        $restaurant->update($addressInfo);
        $restaurant->refresh();
        $user = $restaurant->owners()->first();
        if ($request->hasFile('image'))
        {
            $this->upload($request->file('image'), $restaurant);
        }
        $ownerInfo      = [
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            // 'password'      => isset($request->password) ? Hash::make($request->password) : ,
            'phone'         => $request->phone,
        ];
        if(isset($request->password)) {
            $ownerInfo      = [
                'password'      => Hash::make($request->password),
            ];
        }
        $user->update($ownerInfo);

        return $restaurant->refresh();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Restaurant::find($id);
        // $user = $delete->owners()->first();
        // $user->delete();
        $delete->delete();
        return response()->json([
            'success' => 'Restaurant deleted successfully!'
        ]);
    }
}