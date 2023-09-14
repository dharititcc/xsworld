<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = session('restaurant');
        $category = Category::whereNull('parent_id')->where('restaurant_id',$restaurant->id)->get();
        return view('categories.index',[
            'categories' => $category
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
        //dd($request->all());
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $image = $request->file('photo');
        $profileImage ="";
        if ($image = $request->file('photo'))
        {
            $destinationPath = public_path('/storage/categories');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }
        $categoryArr = [
            'name'          => $request->get('name'),
            'parent_id'     => $request->get('parent_id'),
            'restaurant_id' => $restaurant->id,
        ];

        $newCategory = Category::create($categoryArr);
        $newCategory->attachment()->create([
            'stored_name'   => $profileImage,
            'original_name' => $profileImage,
            //'attachmentable_id' => $category->id,
        ]);
        return $newCategory->refresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
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
    public function update(Request $request, Category $category)
    {
        if(isset($category->id))
        {
            $dataArr = [
                'name' => $request->get('name')
            ];
            $category->update($dataArr);
            $category->refresh();
            $profileImage ="";
            if ($request->hasFile('photo'))
            {
                $image = $request->file('photo');
                $destinationPath = public_path('/storage/categories');
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $category->attachment()->update([
                    'stored_name'   => $profileImage,
                    'original_name' => $profileImage
                ]);
            }

        }
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $delete = Category::find($id);
       $delete->items()->delete();
       $delete->delete();
       return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCategories(Request $request)
    {
        //dd($request->get('category'));
        $category = $request->get('category');
        foreach ($category as $key => $value) {
            //dd($value);
            $delete = Category::find($value);
            $delete->items()->delete();
            $delete->delete();
        }

        return redirect()->back();
    }
}
