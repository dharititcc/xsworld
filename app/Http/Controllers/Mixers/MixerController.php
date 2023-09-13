<?php

namespace App\Http\Controllers\Mixers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantItemsResource;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use DataTables;

class MixerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $restaurant = session('restaurant')->loadMissing(['main_categories']);
        $category = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
        if($category)
        {
            $categories = $category->children;
            $subcategory = $category->children->pluck('id');
        }
        //dd($categories);
        if ($request->ajax())
        {
            if(!empty($request->get('enable')))
            {   //is_available
                $data = $this->updateItemAvailable($request->get('enable'), 1);
            }
            if(!empty($request->get('disable')))
            {    //not available
                $data =  $this->updateItemAvailable($request->get('disable'), 0);
            }
            $data = RestaurantItem::query()
                    ->with(['category', 'restaurant','variations'])
                    ->whereHas('restaurant', function($query) use($restaurant)
                    {
                        return $query->where('restaurants.id', $restaurant->id)->where('restaurant_items.type',3);
                    });
                    if (!empty($request->get('search_main')))
                    {
                        $data = $data->Textsearch(e($request->get('search_main')),"search");

                    }
                    $data = $data->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.mixer-list')->with('categories',$categories);
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
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $image = $request->file('photo');
        $profileImage ="";
        if ($image = $request->file('photo'))
        {
            $destinationPath = public_path('/storage/mixers');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }
        //category
        $category = explode(',',$request->get('category'));
        foreach ($category as $key => $value)
        {
            $restaurantitem                       = new RestaurantItem();
            $restaurantitem->name                 = $request->get('name');
            $restaurantitem->price                = $request->get('price');
            $restaurantitem->type                 = 3;
            $restaurantitem->is_available         = 1;
            $restaurantitem->category_id         = $value;
            $restaurantitem->restaurant_id        = $restaurant->id;
            $restaurantitem->save();
            $restaurantitem->attachment()->create([
                'stored_name'   => $profileImage,
                'original_name' => $profileImage,
                'attachmentable_id' => $restaurantitem->id,
            ]);
        }
        return $restaurantitem;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  RestaurantItem $mixer
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantItem $mixer)
    {
        return new RestaurantItemsResource($mixer);
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
    public function updateItemAvailable($data, $value)
    {
        $updateitems = RestaurantItem::whereIn("id", $data)->update(["is_available" => $value]);
        return $updateitems;
    }
}
