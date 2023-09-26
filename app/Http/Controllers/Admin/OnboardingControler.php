<?php

namespace App\Http\Controllers\Admin;

use App\Models\Onboaring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use File;
use App\Http\Requests\Admin\Oboarding\add;
use App\Http\Requests\Admin\Oboarding\update;

class OnboardingControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $onboardings       = Onboaring::all();
        return view('admin.onboardings.index', compact('onboardings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.onboardings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(add $request)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['title']          =  ['en' => $request->title , 'ar' => $request->title_ar];
            $validated['content']        =  ['en' => $request->content , 'ar' => $request->content_ar];
            //dd( $validated);
            if($request->image){
                $path = public_path('uploads/onboardings/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $file                 = $request->file('image');
                $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']   = $fileName;
                $file->move($path, $fileName);
            }
            $onboarding                       = Onboaring::create($validated);
            flasher(trans('translation.data_addeded_successfully'),'success');
            return redirect()->route('onboardings.index');
        }catch(Exception $e) {
           dd($e);
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
    public function edit(Onboaring $onboarding)
    {
        return view('admin.onboardings.edit' , compact('onboarding'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(update $request,Onboaring $onboarding)
    {
        try{
            $validated                   =  $request->validated();
            $validated['status']         =  (isset($request['status'])) ? 1 : 0 ;
            $validated['title']          =  ['en' => $request->title , 'ar' => $request->title_ar];
            $validated['content']        =  ['en' => $request->content , 'ar' => $request->content_ar];
            if($request->image){
                $path = public_path('uploads/onboardings/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                File::delete($path . $onboarding->image);
                $file                   = $request->file('image');
                $fileName               = uniqid() . '_' . trim($file->getClientOriginalName());
                $validated['image']     = $fileName;
                $file->move($path, $fileName);
            }
            $onboarding                 = $onboarding->update($validated);
            flasher(trans('translation.data_updated_successfully'),'success');
            return redirect()->route('onboardings.index');
        }catch(Exception $e) {
           DB::rollback();
           return redirect()->route('onboardings.index')
           ->with('error',trans('translation.something_wrong_happen'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Onboaring $onboarding)
    {
        $onboarding->delete();
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->route('onboardings.index');
    }
}
