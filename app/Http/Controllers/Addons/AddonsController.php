<?php

namespace App\Http\Controllers\Addons;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantItemsResource;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use DataTables;

class AddonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $restaurant = session('restaurant')->loadMissing(['main_categories']);
        $category = $restaurant->main_categories()->with(['children'])->where('name', 'Food')->first();
        if($category)
        {
            $categories = $category->children;
            $subcategory = $category->children->pluck('id');
        }
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
                        return $query->where('id', $restaurant->id)->where('type',1);
                    });
                    if (!empty($request->get('search_main')))
                    {
                        $data = $data->Textsearch(e($request->get('search_main')),"search");

                    }
                    $data = $data->groupBy('name')->orderByDesc('id')->get();
                    //dd($data);
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.addon-list')->with('categories',$categories);
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
        $category = $request->get('category');
        foreach ($category as $key => $value)
        {
            $restaurantitem                       = new RestaurantItem();
            $restaurantitem->name                 = $request->get('name');
            $restaurantitem->price                = $request->get('price');
            $restaurantitem->type                 = 1;
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
     * @param  int  RestaurantItem $addon
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantItem $addon)
    {
        return new RestaurantItemsResource($addon);
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
