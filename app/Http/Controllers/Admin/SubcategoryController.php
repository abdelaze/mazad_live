<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Exports\ExportSubCategory;
use App\Imports\ImportSubCategory;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\SubCategory\StoreRequest;
use App\Http\Requests\Admin\SubCategory\UpdateRequest;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcats         = SubCategory::with('category')->orderBy('position' , 'ASC')->get();
        return view('admin.subcategories.index', compact('subcats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats      = Category::where('status' , 1)->orderBy('position' , 'ASC')->get();
        return view('admin.subcategories.create', compact('cats'));
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
            $subcat2                      = SubCategory::orderBy('id' , 'DESC')->select('position')->first();
            if($subcat2) {
                $position = $subcat2->position;
            }else {
                $position = 0;
            }
            $subcat                      =  SubCategory::create($validated);
            $subcat['position']          =  $position + 1;
            $subcat->save(); 
            flasher(trans('translation.data_addeded_successfully'),'success');
            return redirect()->route('subcategories.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('subcategories.index')
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
    public function edit(SubCategory $subcategory)
    {
        $cats      =  Category::where('status' , 1)->orderBy('position' , 'ASC')->get();
        return view('admin.subcategories.edit', compact('subcategory','cats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, SubCategory $subcategory)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['name']           =  ['en' => $request->name , 'ar' => $request->name_ar];
            $subcat                      =  $subcategory->update($validated);
            flasher(trans('translation.data_updated_successfully'),'success');
            return redirect()->route('subcategories.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('subcategories.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->route('subcategories.index');
    }

    // sort categoried
    public function SortSubCategories (Request $request) {

        $items    = $request->slecteddata;
        foreach($items as $key => $item) {
            $subcat    = SubCategory::find($item);
            $subcat->position  = $key;
            $subcat->save();
        }

    }

    public function importView(Request $request){
        return view('importSubCategory');
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
        Excel::import(new ImportSubCategory, $request->file('file')->store('files'));
        flasher(trans('translation.data_addeded_successfully'),'success');
        return redirect()->route('subcategories.index');
    }

    public function exportSubCategories(Request $request) {
        return Excel::download(new ExportSubCategory, 'subcategories.xlsx');
    }

}
