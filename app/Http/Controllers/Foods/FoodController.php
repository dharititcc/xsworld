<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use App\Models\RestaurantVariation;
use DataTables;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subcategory = array();
        $categories = array();
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
            $data = RestaurantItem::query()->groupBy('name')
                    ->with(['category', 'restaurant','variations'])
                    ->whereHas('restaurant', function($query) use($restaurant)
                    {
                        return $query->where('id', $restaurant->id)->where('type',2);
                    });
                    if(empty($request->get('category')))
                    {
                        $data = $data->whereHas('category', function($query) use($subcategory)
                        {
                            return $query->whereIn('id', $subcategory);
                        });
                    }
                    else
                    {
                        $cat_id = $request->get('category');
                        $data = $data->whereHas('category', function($query) use($cat_id)
                        {
                            return $query->where('id', $cat_id);
                        });
                    }
                    if (!empty($request->get('search_main')))
                    {
                        $data = $data->Textsearch(e($request->get('search_main')),"search");

                    }
                    $data = $data->latest()->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.foods-list')->with('categories',$categories);

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
        //dd($request->all());
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $image = $request->file('photo');
        $profileImage ="";
        if ($image = $request->file('photo'))
        {
            $destinationPath = public_path('/storage/items');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }
        $categories = $request->get('category_id');

        foreach ($categories as $key => $value)
        {
            $drinkArr = [
                "name"                  => $request->get('name'),
                "category_id"           => $value,
                "description"           => $request->get('description'),
                "price"                 => $request->get('price'),
                "ingredients"           => $request->get('ingredients'),
                "country_of_origin"     => $request->get('country_of_origin'),
                "year_of_production"    => $request->get('year_of_production'),
                "photo"                 => $request->get('name'),
                "is_variable"           => $request->get('is_variable'),
                "is_featured"           => $request->get('is_featured'),
                "is_available"          => 1,
                "type"                  => RestaurantItem::ITEM,
                "restaurant_id"         => $restaurant->id
            ];
            $newRestaurantItem = RestaurantItem::create($drinkArr);

            $variationArr = [];
            if($drinkArr['is_variable'] == 1)
            {
                foreach($request->drink_variation_name as $keys => $drink_variation_name)
                {
                    $variationArr[$keys]['name'] = $drink_variation_name;
                }
                foreach($request->drink_variation_price as $keys => $drink_variation_price)
                {
                    $variationArr[$keys]['price'] = $drink_variation_price;
                }

                foreach($variationArr as $key => $variation)
                {
                    RestaurantVariation::create([
                        'name'                  => $variation['name'],
                        'price'                 => $variation['price'],
                        'restaurant_item_id'    => $newRestaurantItem->id ,
                    ]);
                }
            }


            $newRestaurantItem->attachment()->create([
                'stored_name'   => $profileImage,
                'original_name' => $profileImage
            ]);
        }
        return $newRestaurantItem->refresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantItem $food)
    {
        $categories = RestaurantItem::query()->select('category_id')->where('restaurant_id', $food->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $food->name)->groupBy('category_id')->pluck('category_id')->toArray();
        $restaurantVariation = RestaurantVariation::select('name','price')->where('restaurant_item_id',$food->id)->get()->toArray();
        // dd($food);
        $data = [
            'name'          => $food->name,
            'price'         => $food->price,
            'categories'    => $categories,
            'image'         => $food->attachment ? asset('storage/items/'.$food->attachment->stored_name) : '',
            'restaurant_id' => $food->restaurant_id,
            'ingredients'   => $food->ingredients,
            'country_of_origin' => $food->country_of_origin,
            'year_of_production'    => $food->year_of_production,
            'description'   => $food->description,
            'is_variable'   => $food->is_variable,
            'variation'     => !empty($restaurantVariation) ? $restaurantVariation : [],
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
    public function update(Request $request, RestaurantItem $food)
    {
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $image = $request->file('photo');
        $profileImage ="";
        if ($image = $request->file('photo'))
        {
            $destinationPath = public_path('/storage/items');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }

        //category
        $category = $request->get('category_id');

        //get old Food list
        $oldCategories = RestaurantItem::where('restaurant_id', $food->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $food->name)->get();
        foreach($oldCategories as $del_cal_item)
        {
            // dd($del_cal_item);
            $del_cal_item->delete();
        }
        
        if( !empty( $category ) )
        {
            $oldCategory = RestaurantItem::onlyTrashed()->where('restaurant_id', $food->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $food->name)->whereIn('category_id', $category)->restore();
            foreach( $category as $cat )
            {
                // check if item exist
                $old_cat = RestaurantItem::where('restaurant_id', $food->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $food->name)->where('category_id', $cat)->first();
                if( isset( $old_cat ) )
                {
                    $old_cat->name = $request->get('name');
                    $old_cat->price = $request->get('price');
                    $old_cat->category_id = $cat;

                    $old_cat->description           = $request->get('description');
                    $old_cat->ingredients           = $request->get('ingredients');
                    $old_cat->country_of_origin     = $request->get('country_of_origin');
                    $old_cat->year_of_production    = $request->get('year_of_production');
                    $old_cat->is_variable           = $request->get('is_variable');
                    $old_cat->is_featured           = $request->get('is_featured');
                    $old_cat->save();
                    $old_cat->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $old_cat->id,
                    ]);
                }
                else
                {
                    $foodArr = [
                        "name"                  => $request->get('name'),
                        "category_id"           => $cat,
                        "description"           => $request->get('description'),
                        "price"                 => $request->get('price'),
                        "ingredients"           => $request->get('ingredients'),
                        "country_of_origin"     => $request->get('country_of_origin'),
                        "year_of_production"    => $request->get('year_of_production'),
                        "photo"                 => $request->get('name'),
                        "is_variable"           => $request->get('is_variable'),
                        "is_featured"           => $request->get('is_featured'),
                        "is_available"          => 1,
                        "type"                  => RestaurantItem::ITEM,
                        "restaurant_id"         => $restaurant->id
                    ];
                    $newRestaurantItem = RestaurantItem::create($foodArr);
                    $newRestaurantItem->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage
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
