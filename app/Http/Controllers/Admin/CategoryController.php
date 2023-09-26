<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Exports\ExportCategory;
use App\Imports\ImportCategory;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats         = Category::orderBy('position' , 'asc')->get();
      //  dd($cats);
        return view('admin.categories.index', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['name']           =  ['en' => $request->name , 'ar' => $request->name_ar];
            if($request->image){
                $path = public_path('uploads/categories/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file                 = $request->file('image');
                $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']   = $fileName;
                $file->move($path, $fileName);
            }
            $cat2                      = Category::orderBy('id' , 'DESC')->select('position')->first();
            if($cat2) {
                $position = $cat2->position;
            }else {
                $position = 0;
            }
            $cat                       = Category::create($validated);
            $cat['position']           = $position+1;
            $cat->save(); 
            flasher(trans('translation.data_addeded_successfully'),'success');
            return redirect()->route('categories.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('categories.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
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
    public function edit(Category $category)
    {
        return view('admin.categories.edit' , compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Category $category)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['name']           =  ['en' => $request->name , 'ar' => $request->name_ar];
            if($request->image){
                $path = public_path('uploads/categories/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                File::delete($path . $category->image);
                $file                   = $request->file('image');
                $fileName               = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']     = $fileName;
                $file->move($path, $fileName);
            }
            $cat                 = $category->update($validated);
            flasher(trans('translation.data_updated_successfully'),'success');
            return redirect()->route('categories.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('categories.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        
        $category->delete();
        $path = public_path('uploads/categories/');
        File::delete($path . $category->image);
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->route('categories.index');
    }

    public function getSubcategories(Request $request) {
        $subcategories = SubCategory::where('category_id', $request->categoryId)->get();  
        $data = view('admin.categories.categories_sub')->with('subcategories', $subcategories)->render();
        return response()->json(['success' => true, 'options' => $data]);     
     }

    // sort categoried
  
    public function SortCategories (Request $request) {

        $items    = $request->slecteddata;
        foreach($items as $key => $item) {
            $cat    = Category::find($item);
            $cat->position  = $key;
            $cat->save();
        }
   }

    public function importView(Request $request){
        return view('importCategory');
    }

    public function import(Request $request){
        $validator = Validator::make(
            [
                'file'      => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:xlsx,xls',
            ]
        );
        if($validator->fails()){
            return back()->with('error', $validator->errors()->first());
        }
        
        Excel::import(new ImportCategory, $request->file('file')->store('files'));
        flasher(trans('translation.data_addeded_successfully'),'success');
        return redirect()->route('categories.index');
    }

    public function exportCategories(Request $request){
        return Excel::download(new ExportCategory, 'categories.xlsx');
    }

    public function showAttributes($cat_id) {
          $attributes   = Attribute::where('category_id' , $cat_id)->get();
          return view('admin.categories.show_attributes' , compact('attributes'));     
    }

}
