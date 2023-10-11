<?php

namespace App\Http\Controllers\Drinks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantVariation;
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
            if ($request->hasFile('image'))
            {
                $this->upload($request->file('image'), $newRestaurantItem);
            }
        }
        return $newRestaurantItem->refresh();
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

        $restaurantVariation = RestaurantVariation::select('name','price')->where('restaurant_item_id',$drink->id)->get()->toArray();
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
            'is_variable'   => $drink->is_variable,
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
    public function update(Request $request, RestaurantItem $drink)
    {
        $variationArr   = [];
        $restaurant     = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        if ($request->hasFile('image'))
        {
            $this->upload($request->file('image'), $drink);
        }

        //category
        $category = $request->get('category_id');

        // delete all the variations of the restaurant item
        if( $drink->variations->count() )
        {
            $drink->variations()->delete();
        }

        $restaurantItem = [
            'name'              => $request->name,
            'description'       => $request->description,
            'category_id'       => $request->category_id[0],
            'type'              => RestaurantItem::ITEM,
            'is_variable'       => $request->is_variable,
            'price'             => $request->price,
            'ingredients'       => $request->ingredients,
            'country_of_origin' => $request->country_of_origin,
            'year_of_production'=> $request->year_of_production,
            'type_of_drink'     => $request->type_of_drink,
            'is_featured'       => $request->is_featured
        ];

        // update restaurant item
        $drink->update($restaurantItem);

        // restaurant item variations
        if( $request->is_variable == 1 )
        {
            // check variation array
            $variationNameArr   = $request->drink_variation_name;
            $variationPriceArr  = $request->drink_variation_price;

            if( !empty( $variationNameArr ) )
            {
                foreach( $variationNameArr as $key => $variation )
                {
                    $name   = $variationNameArr[$key];
                    $price  = $variationPriceArr[$key];

                    // get restaurant item variation with same name and price withTrashed
                    $variationItem = $drink->variations()->where('name', $name)->where('price', $price)->withTrashed()->first();

                    if( isset( $variationItem->id ) )
                    {
                        $variationItem->restore();
                    }
                    else
                    {
                        $variationArr = [
                            'name'      => $name,
                            'price'     => $price
                        ];

                        // store restaurant item variation
                        $drink->variations()->create($variationArr);
                    }
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

    /**
     * Method upload
     *
     * @param $file $file [explicite description]
     * @param \App\Models\RestaurantItem $model [explicite description]
     *
     * @return void
     */
    private function upload($file, RestaurantItem $model)
    {
        //Move Uploaded File
        $destinationPath = public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'items');
        $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
        $file->move($destinationPath, $profileImage);

        $model->attachment()->delete();

        $model->attachment()->create([
            'stored_name'   => $profileImage,
            'original_name' => $profileImage
        ]);
    }
}
