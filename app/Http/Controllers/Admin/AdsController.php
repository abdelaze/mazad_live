<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\StoreAdRequest;
use App\Http\Requests\Admin\updateAdRequest;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads     = Ad::all();
        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdRequest $request) 
    {
        try{
            $validated                   = $request->validated();
            $validated                   = $request->safe()->only(['link', 'image','status']);
            $validated['status']         = (isset($request['status'])) ? 1 : 0 ;
            if($request->image){
                $path = public_path('uploads/ads/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file                 = $request->file('image');
                $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']   = $fileName;
                $file->move($path, $fileName);
            }
            $ad                       = Ad::create($validated);
            flasher(trans('translation.data_addeded_successfully'),'success');
            return redirect()->to('admin/ads');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('ads.index')
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
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit' , compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateAdRequest $request,Ad $ad)
    {
        try{
            $validated                   = $request->validated();
            $validated['status']         = (isset($request['status'])) ? 1 : 0 ;
            if($request->image){
                $path = public_path('uploads/ads/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                File::delete($path . $ad->image);
                $file                   = $request->file('image');
                $fileName               = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']     = $fileName;
                $file->move($path, $fileName);
            }
            $ad                         = $ad->update($validated);
            flasher(trans('translation.data_updated_successfully'),'success');
            return redirect()->to('admin/ads');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('ads.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->to('admin/ads');
    }
}
