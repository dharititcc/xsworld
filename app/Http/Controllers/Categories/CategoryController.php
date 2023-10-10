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
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $categoryArr = [
            'name'          => $request->get('name'),
            'parent_id'     => $request->get('parent_id'),
            'restaurant_id' => $restaurant->id,
        ];

        $newCategory = Category::create($categoryArr);
        $newCategory->refresh();
        if ($request->hasFile('photo'))
        {
            $this->upload($request->file('photo'), $newCategory);
        }
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
            if ($request->hasFile('photo'))
            {
                $this->upload($request->file('photo'), $category);
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
            if( $delete->items->count() )
            {
                $delete->items()->delete();
            }
            $delete->delete();
        }

        return redirect()->back();
    }

    /**
     * Method upload
     *
     * @param $file $file [explicite description]
     * @param \App\Models\Category $model [explicite description]
     *
     * @return void
     */
    private function upload($file, $model)
    {
        //Move Uploaded File
        $destinationPath = public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'categories');
        $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
        $file->move($destinationPath, $profileImage);

        $model->attachment()->delete();

        $model->attachment()->create([
            'stored_name'   => $profileImage,
            'original_name' => $profileImage
        ]);
    }
}
