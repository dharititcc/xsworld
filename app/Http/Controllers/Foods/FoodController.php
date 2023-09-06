<?php

namespace App\Http\Controllers\Foods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
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
            $data = RestaurantItem::query()
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
                    $data = $data->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
