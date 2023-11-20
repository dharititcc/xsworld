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
        $foodCategory = $restaurant->main_categories()->with(['children'])->where('name', 'Food')->first();
        if($foodCategory)
        {
            $foodCategories = $foodCategory->children;
            $foodSubCategory = $foodCategory->children->pluck('id');
        }
        $drinkCategory = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
        if($drinkCategory)
        {
            $drinkCategories = $drinkCategory->children;
            $drinkSubCategory = $drinkCategory->children->pluck('id');
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
                        return $query->where('restaurants.id', $restaurant->id)->where('restaurant_items.type', RestaurantItem::ADDON);
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

        return view('restaurant.addon-list', [
            'food_categories' => isset($foodCategories) ? $foodCategories : [],
            'drink_categories'=> isset($drinkCategories) ? $drinkCategories : [],
            'restaurant'      => $restaurant
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
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $category   = $request->get('category');
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
        $addon_categories = RestaurantItem::select('category_id')->where(['restaurant_id'=>$addon->restaurant_id,'type' => RestaurantItem::ADDON])
                    ->where('name',$addon->name)->groupBy('category_id')->pluck('category_id')->toArray();
        $addon = [
            'name'          =>  $addon->name,
            'price'         =>  $addon->price,
            'categories'    =>  $addon_categories,
            'image'         =>  $addon->attachment ?? asset('storage/addons/'.$addon->attachment->stored_name),
            'restaurant_id' =>  $addon->restaurant_id,
        ];
        return response()->json([
            'status' => true,
            'addon'   => $addon
        ]);
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
    public function update(Request $request, RestaurantItem $addon)
    {
        $restaurant     = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $category       = $request->get('category');
        // get old addons list
        $oldCategories  = RestaurantItem::where(['restaurant_id'=>$addon->restaurant_id,'type' => RestaurantItem::ADDON])
                            ->where('name',$addon->name)->pluck('category_id')->toArray();
        $changeArr      = [];

        if(!empty($oldCategories))
        {
            foreach($oldCategories as $oldcat)
            {
                // dd($oldcat);
                if(!in_array($oldcat, $category))
                {
                    $changeArr[] = $oldcat;
                }
            }
            // dd($changeArr);
        }

        if(!empty($changeArr))
        {
            $items = RestaurantItem::where(['restaurant_id'=>$addon->restaurant_id,'type' => RestaurantItem::ADDON])
                    ->where('name',$addon->name)->whereIn('category_id',$changeArr)->get();
            if($items->count()) {
                foreach($items as $item)
                {
                    $item->delete();
                }
            }
        }

        if(!empty($category))
        {
            foreach($category as $cat)
            {
                $oldAddon = RestaurantItem::where('restaurant_id', $addon->restaurant_id)
                            ->where('type', RestaurantItem::ADDON)
                            ->where('category_id',$cat)
                            ->first();
                if(isset($oldAddon->id))
                {
                    $oldAddon->name = $request->name;
                    $oldAddon->price = $request->price;
                    $oldAddon->category_id = $cat;
                    $oldAddon->save();

                    $oldAddon->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $oldAddon->id,
                    ]);
                } else {
                    //insert
                    $newAddon = RestaurantItem::create([
                        'name' => $request->name,
                        'price' => $request->price,
                        'type' => RestaurantItem::ADDON,
                        'is_available'      => 1,
                        'category_id' => $cat,
                        'restaurant_id'     => $restaurant->id
                    ]);

                    $newAddon->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $newAddon->id,
                    ]);
                }

            }
            return true;
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
        //
    }
    public function updateItemAvailable($data, $value)
    {
        $updateitems = RestaurantItem::whereIn("id", $data)->update(["is_available" => $value]);
        return $updateitems;
    }
}
