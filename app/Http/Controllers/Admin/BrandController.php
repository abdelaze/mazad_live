<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Exports\BrandExport;
use App\Imports\BrandImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Brand\StoreRequest;
use App\Http\Requests\Admin\Brand\UpdateRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands        = Brand::orderBy('id' , 'desc')->get();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
       // dd($request->all());
        try{
            $validated                   =  $request->validated();
           // dd( $validated );
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['name']           =  ['en' => $request->name , 'ar' => $request->name_ar];
            if($request->image && !empty($request->image)){
                $path = public_path('uploads/brands/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file                 = $request->file('image');
                $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']   = $fileName;
                $file->move($path, $fileName);
            }
          
            $brand                       = Brand::create($validated);
            flasher(trans('translation.data_addeded_successfully'),'success');
            return redirect()->route('brands.index');
        }catch(Exception $e) {
            dd($e);
           DB::rollback();
           return redirect()->route('brands.index')
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
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit' , compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Brand $brand)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['name']           =  ['en' => $request->name , 'ar' => $request->name_ar];
            if($request->image && !empty($request->image)){
                $path = public_path('uploads/brands/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                if(!empty($brand->image)) {
                    File::delete($path . $brand->image);
                }
                $file                   = $request->file('image');
                $fileName               = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']     = $fileName;
                $file->move($path, $fileName);
            }
            $brand                      = $brand->update($validated);
            flasher(trans('translation.data_updated_successfully'),'success');
            return redirect()->route('brands.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('brands.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        
        $brand->delete();
        $path = public_path('uploads/brands/');
        File::delete($path . $brand->image);
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->route('brands.index');
    }

  

    public function importView(Request $request){
        return view('importBrand');
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
        
        Excel::import(new BrandImport, $request->file('file')->store('files'));
        flasher(trans('translation.data_addeded_successfully'),'success');
        return redirect()->route('brands.index');
    }

    public function exportBrands(Request $request){
        return Excel::download(new BrandExport, 'brands.xlsx');
    }
}
