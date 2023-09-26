<?php

namespace App\Http\Controllers\Api;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PackageResource;
use App\Models\{Package,Salon,SalonPackage};
use App\Http\Controllers\Api\BaseController as BaseController;

class PackageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $packages = Package::where('status' , 1)->get();
        if($packages) {
            foreach($packages as $key=>$package) {
                if($package->cost == 0) {

                     $salon_package = SalonPackage::where(['package_id' => $package->id , 'user_id' => Auth::guard('api')->user()->id, 'status' => 1])->first();
                     if(!empty($salon_package)) {
                        unset($packages[$key]);
                     }else {
                        $package->subscriped = 0;
                     }
                }else {
                    $salon_package = SalonPackage::where(['package_id' => $package->id , 'user_id' => Auth::guard('api')->user()->id, 'status' => 1])->where('end_date'  , '>=' ,date('Y-m-d' , strtotime(Carbon::now())))->first();
                     if(!empty($salon_package)) {
                        $package->subscriped = 1;
                     }else {
                        $package->subscriped = 0;
                     }
                }
            }
            return $this->sendResponse($packages , 'data returnd successfully.');

        }else {
            return $this->sendResponse($packages , 'data returnd successfully.');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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

    public function Subscription(Request $request) {
        $validator = Validator::make($request->all(), [
            'package_id'    => 'required',
            'payment_type'  =>  'required|in:vodafone_cash,online',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $package = Package::where(['id' =>  $request->package_id ,'status' => 1])->first();
        if(!empty($package)) {
            $salon_package = SalonPackage::where(['user_id' => Auth::guard('api')->user()->id])->where('end_date'  ,'>=', date('Y-m-d' , strtotime(Carbon::now())))->orderBy('id' , 'DESC')->first();
            if(!empty($salon_package)) {
                $end_date                        = date('Y-m-d' , strtotime($salon_package->end_date));
                $new_salon_package               = new  SalonPackage ();
                $new_salon_package->package_id   = $package->id;
                $new_salon_package->user_id     = Auth::guard('api')->user()->id;
                $new_salon_package->start_date   = date('Y-m-d' , strtotime("+1 day",  strtotime($end_date)));
                $new_salon_package->end_date     = date('Y-m-d' , strtotime("+".($package->duration*30). "day",  strtotime($end_date)));
                if($request->payment_type == "online") {
                    $new_salon_package->status   = 1 ;
                }
                $new_salon_package->payment_type =  $request->payment_type;
                $new_salon_package->save();
                $success = true;
                return $this->sendResponse($success , 'subscription done successfully.');
            }else {
               $new_salon_package = new  SalonPackage ();
               $new_salon_package->package_id   = $package->id;
               $new_salon_package->user_id      = Auth::guard('api')->user()->id;
               $new_salon_package->start_date   = date('Y-m-d' , strtotime(Carbon::now()));
               $new_salon_package->end_date     = date('Y-m-d' , strtotime(Carbon::now()->addMonths($package->duration)));
               if($request->payment_type == "online") {
                $new_salon_package->status   = 1 ;
               }
               $new_salon_package->payment_type =  $request->payment_type;
               $new_salon_package->save();
               $success = true;
               return $this->sendResponse($success , 'subscription done successfully.');
            }
        }else {
            $success = false;
            return $this->sendResponse($success , 'This Package Not Available.');
        }


    }
}
