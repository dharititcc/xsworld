<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantController extends APIController
{
    /**
     * Method getVenueSearch
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVenueSearch(Request $request)
    {
        $user = auth()->user();
       // dd($user);
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $restorant = Restaurant::select('restaurants.*',
        DB::raw("6371 * acos(cos(radians(" . $latitude . "))
        * cos(radians(restaurants.latitude)) * cos(radians(restaurants.longitude) - radians(" . $longitude . "))
        + sin(radians(" .$latitude. ")) * sin(radians(restaurants.latitude))) AS distance"));

        if (!is_null($request->name)) 
            $restorant = $restorant->where('restaurants.name','like',"%$request->name%");

        if (!is_null($request->drink))
        {
            $drink = $request->drink;
            $restorant = $restorant->whereHas('items',function ($query) use ($drink) {
                     return $query->where('items.name', 'like', "%{$drink}%");
                    }
                
            );
        }
        $restorant = $restorant->with('items')->get()->toArray();
        return $this->respond([
            'status'    =>  true,
            'message'   =>  'get venue successfull',
            'item'      =>  $restorant,
        ]);
        //dd($restorant);
        //$data = $restorant->toArray();
        //return $this->respondWithPagination($restorant, $data);
        //where('items.name','like',"%$request->drink%");

    }
}
