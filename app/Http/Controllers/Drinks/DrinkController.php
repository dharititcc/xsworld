<?php

namespace App\Http\Controllers\Drinks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantItem;
use App\Models\Category;
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
        $restaurant = session('restaurant')->loadMissing(['main_categories']);

        $category = $restaurant->main_categories()->with(['children'])->where('name', 'Drinks')->first();
        if($category)
        {
            $categories = $category->children;
            $subcategory = $category->children->pluck('id');
        }
        if ($request->ajax())
        {
            $data = RestaurantItem::query()
                    ->with(['category', 'restaurant'])
                    ->whereHas('restaurant', function($query) use($restaurant)
                    {
                        return $query->where('id', $restaurant->id);
                    })
                     ->whereHas('category', function($query) use($subcategory)
                     {
                         return $query->whereIn('id', $subcategory);
                     })
                    ->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('restaurant.drinks-list')->with('categories');
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
}
