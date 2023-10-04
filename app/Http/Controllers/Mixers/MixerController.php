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
        $restaurant     = session('restaurant')->loadMissing(['main_categories']);
        $drinkCategory  = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
        if($drinkCategory)
        {
            $drinkCategories = $drinkCategory->children;
            $drinkSubCategory = $drinkCategory->children->pluck('id');
        }
        $foodCategory  = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
        if($foodCategory)
        {
            $foodCategories = $foodCategory->children;
            $foodSubCategory = $foodCategory->children->pluck('id');
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
            $data = RestaurantItem::query()->groupBy('name')
                    ->with(['category', 'restaurant','variations'])
                    ->whereHas('restaurant', function($query) use($restaurant)
                    {
                        return $query->where('restaurants.id', $restaurant->id)->where('restaurant_items.type', RestaurantItem::MIXER);
                    });
                    if (!empty($request->get('search_main')))
                    {
                        $data = $data->Textsearch(e($request->get('search_main')),"search");
                    }
                    $data = $data->orderByDesc('id')->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.mixer-list', [
            'drink_categories'  => isset($drinkCategories) ? $drinkCategories : [],
            'food_categories'   => isset($foodCategories) ? $foodCategories : []
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
        $categories = RestaurantItem::query()->select('category_id')->where('restaurant_id', $mixer->restaurant_id)->where('type', RestaurantItem::MIXER)->where('name', $mixer->name)->groupBy('category_id')->pluck('category_id')->toArray();

        $data = [
            'name'          => $mixer->name,
            'price'         => $mixer->price,
            'categories'    => $categories,
            'image'         => $mixer->attachment ? asset('storage/mixers/'.$mixer->attachment->stored_name) : '',
            'restaurant_id' => $mixer->restaurant_id,
        ];

        return response()->json([
            'status' => true,
            'data'   => $data
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
    public function update(Request $request, RestaurantItem $mixer)
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

        // get old mixers list
        $oldCategories = RestaurantItem::where('restaurant_id', $mixer->restaurant_id)->where('type', RestaurantItem::MIXER)->where('name', $mixer->name)->get();
        foreach($oldCategories as $del_cal_item)
        {
            $del_cal_item->delete();
        }
        
        if( !empty( $category ) )
        {
            $oldCategory = RestaurantItem::onlyTrashed()->where('restaurant_id', $mixer->restaurant_id)->where('type', RestaurantItem::MIXER)->where('name', $mixer->name)->whereIn('category_id', $category)->restore();
            foreach( $category as $cat )
            {
                // check if item exist
                $oldMixer = RestaurantItem::where('restaurant_id', $mixer->restaurant_id)->where('type', RestaurantItem::MIXER)->where('name', $mixer->name)->where('category_id', $cat)->first();
                if( isset( $oldMixer ) )
                {
                    $oldMixer->name = $request->get('name');
                    $oldMixer->price = $request->get('price');
                    $oldMixer->category_id = $cat;
                    $oldMixer->save();

                    $oldMixer->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $oldMixer->id,
                    ]);
                }
                else
                {
                    $newMixer = RestaurantItem::create(
                        [
                            'name'              => $request->get('name'),
                            'price'             => $request->get('price'),
                            'type'              => RestaurantItem::MIXER,
                            'is_available'      => 1,
                            'category_id'       => $cat,
                            'restaurant_id'     => $restaurant->id
                        ]
                    );

                    $newMixer->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $newMixer->id,
                    ]);
                }
            }
        }
        return true;
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
