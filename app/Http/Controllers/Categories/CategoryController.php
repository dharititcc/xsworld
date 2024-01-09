<?php

namespace App\Http\Controllers\Categories;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

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
    public function store(CategoryRequest $request)
    {
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);
        $categoryArr = [
            'name'          => $request->get('name'),
            'parent_id'     => $request->get('parent_id'),
            'restaurant_id' => $restaurant->id,
        ];

        // check if category name exist

        if( $this->checkUniqueCategory($request, $restaurant) )
        {
            throw new GeneralException('The category name is already exist.');
        }

        $newCategory = Category::create($categoryArr);
        $newCategory->refresh();
        if ($request->hasFile('photo'))
        {
            $this->upload($request->file('photo'), $newCategory);
        }
        return $newCategory->refresh();
    }

    /**
     * Method checkUniqueCategory
     *
     * @param Request $request [explicite description]
     * @param Restaurant $restaurant [explicite description]
     *
     * @return int
     */
    private function checkUniqueCategory(Request  $request, Restaurant $restaurant)
    {
        $text = htmlentities(strtolower($request->name));
        return Category::whereRaw(DB::raw("LOWER(`name`) = '{$text}'"))->where('restaurant_id', $restaurant->id)->count();
    }

    public function categoryName(Request $request)
    {
        $restaurant = session('restaurant')->loadMissing(['main_categories', 'main_categories.children']);

        $text = strtolower($request->name);

        if( $this->checkUniqueCategory($request, $restaurant) )
        {
            throw new GeneralException('The category name is already exist.');
        }

        $newCategory = Category::updateOrCreate([
            'restaurant_id' => $restaurant->id,
            'name'          => $request->get('name'),
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
     * @param  UpdateCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
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
        // dd($request->get('category'));
        $category = $request->get('category');

        if( !empty( $category ) )
        {
            foreach ($category as $key => $value)
            {
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

        throw new GeneralException('Please select atleast one category.');
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
