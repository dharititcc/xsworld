<?php

namespace App\Http\Controllers\Drinks;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantVariation;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DrinkImport;
use App\Imports\DrinksImport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'country']);
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
            $data = RestaurantItem::select([
                'restaurant_items.id',
                'restaurant_items.name',
                'restaurant_items.type',
                'categories.name AS category_name',
                'restaurant_items.is_available',
                'restaurant_items.is_featured',
                'restaurant_items.created_at',
                'restaurant_items.price'
            ])
                    ->with(['category', 'restaurant','variations'])
                    ->leftJoin('categories', 'categories.id', '=', 'restaurant_items.category_id')
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

        return view('restaurant.drinks-list')->with(
            [
                'categories' => $categories,
                'restaurant' => $restaurant
            ]
        );
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

        if( $request->is_variable == 0 )
        {
            $request->validate([
                'price' => 'required|numeric|between:0.01,9999999999.99'
            ],[
                'price.between' => "Please enter a valid price format (e.g., 100.50)"
            ]);
        }
        foreach ($categories as $key => $value)
        {
            $drinkArr = [
                "name"                  => $request->get('name'),
                "category_id"           => $value,
                "description"           => $request->get('description'),
                "price"                 => $request->get('price') ?? 0,
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

            // check if product exist

            if( $this->checkUniqueDrink($request, $restaurant) )
            {
                throw new GeneralException('The Product is already exist.');
            }

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
     * Method checkUniqueDrink
     *
     * @param Request $request [explicite description]
     * @param Restaurant $restaurant [explicite description]
     *
     * @return int
     */
    private function checkUniqueDrink(Request  $request, Restaurant $restaurant)
    {
        $text = htmlentities(strtolower($request->name));
        return RestaurantItem::whereRaw(DB::raw("LOWER(`name`) = '{$text}'"))->where('restaurant_id', $restaurant->id)->where('category_id', $request->get('category_id'))->count();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantItem $drink)
    {
        $restaurantVariation = RestaurantVariation::select('name','price')->where('restaurant_item_id',$drink->id)->get()->toArray();
        $data = [
            'name'          => $drink->name,
            'price'         => $drink->price,
            'categories'    => [$drink->category_id],
            'image'         => $drink->attachment ? asset('storage/items/'.$drink->attachment->stored_name) : '',
            'restaurant_id' => $drink->restaurant_id,
            'ingredients'   => $drink->ingredients,
            'country_of_origin' => $drink->country_of_origin,
            'year_of_production'    => $drink->year_of_production,
            'description'   => $drink->description,
            'type_of_drink' => $drink->type_of_drink,
            'is_variable'   => $drink->is_variable,
            'is_featured'   => $drink->is_featured,
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

        if( $request->is_variable == 0 )
        {
            $request->validate([
                'price' => 'required|numeric|between:0.01,9999999999.99'
            ],[
                'price.between' => "Please enter a valid price format (e.g., 100.50)"
            ]);
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
            'price'             => $request->price ?? 0,
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

    public function favoriteStatusUpdate(Request $request)
    {
        $restaurant = session('restaurant');
        $res_featured = RestaurantItem::find($request->id);
        $res_featured->is_featured = $request->is_featured;
        $res_featured->save();
        return response()->json(['success'=>'featured change successfully.']);
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

    /**
     * Method SampleFileDrink
     *
     * @return void
     */
    public function SampleFileDrink()
    {
        $destinationPath = public_path('/XSWorld_sample_data_drink.xlsx');
        return response()->download($destinationPath);
    }

    /**
     * Method uploadData
     *
     * @param Request $request [explicite description]
     *
     * @return mixed
     */
    public function uploadDrinkData(Request $request)
    {
        $file = $request->file('upload_data');

        $validator = Validator::make(
            [
                'file'      => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file'       => 'required',
                'extension'  => 'required|in:xlsx,xls',
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        if($file){
            // $data = Excel::import(new DrinksImport(), $file); // use for default imports
            $data = Excel::toArray(new DrinkImport, $file); // use for get data without heading row
            // $data = Excel::toArray([], $file);

            // $this->validateExcel($data , $restaurant);

            // if($validateDrink == true)
            // {

            // }
            // exit;

            $shouldRedirectProduct = false;
            foreach($data[0] as $key => $row)
            {
                $text = htmlentities(strtolower($row[1]));

                $category = Category::where([
                                'name'          => $row[2],
                                'restaurant_id' => $restaurant->id
                            ])->first();

                $restaurants = RestaurantItem::whereRaw(DB::raw("LOWER(`name`) = '{$text}'"))
                ->where('restaurant_id', $restaurant->id)
                ->where('category_id', $category->id)
                ->count();
                if($restaurants == 1)
                {
                    $shouldRedirectProduct = true;
                    $category_message = "The Product is already exist with ".$row[2]." . Please enter valid items in row no ".$key+2;
                    break;
                }
            }
            if ($shouldRedirectProduct == true) {
                return redirect()->route('restaurants.drinks.index')->with('message', $category_message);
            }

            $shouldRedirect = false;
            foreach($data[0] as $key => $row)
            {
                $category = Category::where([
                                'name'          => $row[2],
                                'restaurant_id' => $restaurant->id
                            ])->count();
                if($category == 0)
                {
                    $shouldRedirect = true;
                    $category_message = $row[2]." category is not found.Please enter valid category in row no ".$key+2;
                    break;
                }
            }
            if ($shouldRedirect == true) {
                return redirect()->route('restaurants.drinks.index')->with('message', $category_message);
            }

            foreach($data[0] as $row)
            {
                $category = Category::where([
                    'name' => $row[2],
                    'restaurant_id' => $restaurant->id
                    ])->first();

                $drinkArr = [
                    "name"                  => $row[1],
                    "category_id"           => $category->id,
                    "description"           => $row[8],
                    "price"                 => $row[3],
                    "country_of_origin"     => $row[5],
                    "ingredients"           => $row[4],
                    "type_of_drink"         => $row[7],
                    "year_of_production"    => $row[6],
                    "is_available"          => $row[9],
                    "is_featured"           => $row[10],
                    "is_variable"           => $row[11],
                    "type"                  => RestaurantItem::ITEM,
                    "restaurant_id"         => $restaurant->id,
                    "created_at"            => Carbon::now(),
                    "updated_at"            => Carbon::now(),
                ];
                $newRestaurantItem = RestaurantItem::create($drinkArr);

                if($drinkArr['is_variable'] == 1)
                {
                    foreach($data[1] as $variation_row)
                    {
                        RestaurantVariation::create([
                            'name'                  => $variation_row[1],
                            'price'                 => $variation_row[2],
                            'restaurant_item_id'    => $newRestaurantItem->id ,
                        ]);
                    }
                }
            }
            return redirect()->route('restaurants.drinks.index')->with('message', 'Drinks imported successfully!');
        }
    }

    /**
     * Method validateExcel
     *
     * @param array $data [explicite description]
     * @param Restaurant $restaurant [explicite description]
     *
     * @return mixed
     */
    public function validateExcel(array $data ,Restaurant $restaurant)
    {
        $shouldRedirect = false;
        foreach($data[0] as $key => $row)
        {
            $category = Category::where([
                            'name'          => $row[2],
                            'restaurant_id' => $restaurant->id
                        ])->count();

            if($category == 0)
            {
                $shouldRedirect = true;
                $category_message = "This category is not found.Please enter valid category in row no ".$key+1;
                break;
            }
        }

        if (isset($shouldRedirect)) {
            return redirect()->route('restaurants.drinks.index')->with('message', $category_message);
        }
    }
}
