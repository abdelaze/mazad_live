<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Api\Option      as OptionResource;
use App\Http\Resources\Api\Category as CategoryResource;
use App\Http\Resources\Api\SubCategory as SubCategoryResource;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories    = CategoryResource::collection(Category::where('status' , 1)->select('id', 'name', 'image')->get());
        return $this->sendResponse($categories , 'data returned successfully.' );
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
       // $subcategories    = SubCategoryResource::collection(SubCategory::where(['category_id'  => $id , 'status' => 1])->select('id', 'name')->get());
          return  CategoryResource::collection(Category::with('subcategories.brands' , 'brands' , 'attributes')->where('id' ,  $id)->get()); 
      // return $this->sendResponse( $category , 'data returned successfully.' );
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

    public function all_options($id , Attribute $attr) {
        
        return OptionResource::collection($attr->where('category_id' , $id)->get());
    }

}
