<?php

namespace App\Http\Controllers\Drinks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use App\Models\Category;
use App\Models\Restaurant;
use DataTables;

class DrinkController extends Controller
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
        $category = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
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
                        return $query->where('restaurants.id', $restaurant->id)->where('restaurant_items.type',RestaurantItem::ITEM);
                    });
                    if(empty($request->get('category')))
                    {
                        $data = $data->whereHas('category', function($query) use($subcategory)
                        {
                            return $query->whereIn('categories.id', $subcategory);
                        });
                    }
                    else
                    {
                        $cat_id = $request->get('category');
                        $data = $data->whereHas('category', function($query) use($cat_id)
                        {
                            return $query->where('categories.id', $cat_id);
                        });
                    }
                    if (!empty($request->get('search_main')))
                    {
                        $data = $data->Textsearch(e($request->get('search_main')),"search");

                    }
                    $data = $data->orderByDesc('id')->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.drinks-list')->with('categories',$categories);
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
                "type_of_drink"         => $request->get('type_of_drink'),
                "year_of_production"    => $request->get('year_of_production'),
                "photo"                 => $request->get('name'),
                "is_variable"           => $request->get('is_variable'),
                "is_featured"           => $request->get('is_featured'),
                "is_available"          => 1,
                "type"                  => RestaurantItem::ITEM,
                "restaurant_id"         => $restaurant->id
            ];
            $newRestaurantItem = RestaurantItem::create($drinkArr);
            $newRestaurantItem->attachment()->create([
                'stored_name'   => $profileImage,
                'original_name' => $profileImage
            ]);
        }
        return $newRestaurantItem->refresh();
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantItem $drink)
    {
        $categories = RestaurantItem::query()->select('category_id')->where('restaurant_id', $drink->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $drink->name)->groupBy('category_id')->pluck('category_id')->toArray();

        $data = [
            'name'          => $drink->name,
            'price'         => $drink->price,
            'categories'    => $categories,
            'image'         => $drink->attachment ? asset('storage/items/'.$drink->attachment->stored_name) : '',
            'restaurant_id' => $drink->restaurant_id,
            'ingredients'   => $drink->ingredients,
            'country_of_origin' => $drink->country_of_origin,
            'year_of_production'    => $drink->year_of_production,
            'description'   => $drink->description,
            'type_of_drink' => $drink->type_of_drink,
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
    public function update(Request $request, RestaurantItem $drink)
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

        // get old Drink list
        $oldCategories = RestaurantItem::where('restaurant_id', $drink->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $drink->name)->get()->pluck('category_id')->toArray();
        $changeArr = [];

        if( !empty( $oldCategories ) )
        {
            foreach( $oldCategories as $oldCat )
            {
                if( !in_array($oldCat, $category) )
                {
                    $changeArr[] = $oldCat;
                }
            }
        }

        if( !empty( $changeArr ) )
        {
            $items = RestaurantItem::where('restaurant_id', $drink->restaurant_id)->where('type', RestaurantItem::ITEM)->where('name', $drink->name)->whereIn('category_id', $changeArr)->get();

            if( $items->count() )
            {
                foreach( $items as $item )
                {
                    // delete
                    $item->delete();
                }
            }
        }

        if( !empty( $category ) )
        {
            foreach( $category as $cat )
            {
                $oldDrink = RestaurantItem::where('restaurant_id', $drink->restaurant_id)
                            ->where('type', RestaurantItem::ITEM)
                            ->where('category_id', $cat)
                            ->first();

                if( isset( $oldDrink->id ) )
                {
                    // update
                    $oldDrink->name = $request->get('name');
                    $oldDrink->price = $request->get('price');
                    $oldDrink->category_id = $cat;

                    $oldDrink->description           = $request->get('description');
                    $oldDrink->ingredients           = $request->get('ingredients');
                    $oldDrink->country_of_origin     = $request->get('country_of_origin');
                    $oldDrink->type_of_drink         = $request->get('type_of_drink');
                    $oldDrink->year_of_production    = $request->get('year_of_production');
                    $oldDrink->is_variable           = $request->get('is_variable');
                    $oldDrink->is_featured           = $request->get('is_featured');
                    $oldDrink->save();

                    $oldDrink->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $oldDrink->id,
                    ]);
                }
                else
                {
                    // insert
                    $newDrink = RestaurantItem::create(
                        [

                            "name"                  => $request->get('name'),
                            "category_id"           => $cat,
                            "description"           => $request->get('description'),
                            "price"                 => $request->get('price'),
                            "ingredients"           => $request->get('ingredients'),
                            "country_of_origin"     => $request->get('country_of_origin'),
                            "type_of_drink"         => $request->get('type_of_drink'),
                            "year_of_production"    => $request->get('year_of_production'),
                            "is_variable"           => $request->get('is_variable'),
                            "is_featured"           => $request->get('is_featured'),
                            "is_available"          => 1,
                            "type"                  => RestaurantItem::ITEM,
                            "restaurant_id"         => $restaurant->id
                        ]
                    );

                    $newDrink->attachment()->create([
                        'stored_name'   => $profileImage,
                        'original_name' => $profileImage,
                        'attachmentable_id' => $newDrink->id,
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
