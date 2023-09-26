<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
//use App\Http\Resources\SalonService;
use App\Models\Salon;
use App\Models\SalonOption;
use App\Models\SalonService;
use Illuminate\Http\Request;
use Validator;



class AdminServiceController extends BaseController
{
    public function gatBasicServices(Request $request) {
        $validator = Validator::make($request->all(), [
            'salon_id'  => ['required', 'exists:salons,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
       $services = SalonService::select('id', 'service_name_ar','service_name_en', 'service_name_de', 'service_name_tu')
                               ->with(['option' =>  function($q)  use ($request){
                                    $q->where('salon_id',  $request->salon_id);
                                }])->get();
        return $this->sendResponse(true, $services);
    }

    public function gatSalonServices(Request $request){
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $services = SalonOption::select('salon_id', 'service_price', 'service_time', 'service_time_sign_ar', 'service_time_sign_en', 'service_time_sign_de', 'service_time_sign_tu', 'service_name_ar','service_name_en', 'service_name_de', 'service_name_tu', 'status')
            ->where(['salon_id' => $request->salon_id, 'salon_service_id' => null])->get();
        return $this->sendResponse(true, $services);
    }

    public function updateServices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id'     => ['required', 'exists:salons,id'],
            'own_services' => ['required', 'array','min:1'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $salon      = Salon::where('id' , $request->salon_id)->with('salon_own_options')->first();
        if($salon) {
            $salon->salon_own_options()->delete();
        }
        $options    = $request->own_services;
        $salon_id   = $request->salon_id;
        $lang                                         =  $request->hasHeader('lang') ;

        foreach( $options as  $optionn) {
            $option                                   =   new SalonOption();
            if($lang == 'ar') {
                $option->service_name_ar              =   $optionn['service_name'];
            }else if($lang == 'tu') {
                $option->service_name_tu              =   $optionn['service_name'];
            }else if($lang == 'de') {
                $option->service_name_de              =   $optionn['service_name'];
            }else {
                $option->service_name_en              =   $optionn['service_name'];
            }
            $option->service_price                    =   $optionn['service_price'];
            $option->service_time                     =   $optionn['service_time'];
            if(  $optionn['service_time_sign'] =="min") {
                $option->service_time_sign_en             =  "minute";
                $option->service_time_sign_de             =  "minute";
                $option->service_time_sign_ar             =  "دقيقه";
                $option->service_time_sign_tu             =  "dakika";
            }else {
                $option->service_time_sign_en             =  "hour";
                $option->service_time_sign_de             =  "stunde";
                $option->service_time_sign_ar             =  "ساعه";
                $option->service_time_sign_tu             =  "saat";
            }
            $option->status                               =   (isset($optionn['status'])) ? 1 : 0;
            $option->own                                  =   1;
            $option->salon_id                             =   $salon_id;
            $option->save();
        }

        return $this->sendResponse(true, "service added successfully");
    }

    public function addService(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'salon_id'          => ['required', 'exists:salons,id'],
            'salon_service_id ' => ['nullable', 'exists:salon_services,id'],
            'service_name'      => ['required', 'string', 'min:3', 'max:50'],
            'service_price'     => ['required'],
            'service_time'      => ['required'],
            'service_time_sign' => ['required'],
        ]);

        if (!$request->hasHeader('lang')) {
            $errors = [];
            array_push($errors, ['lang' => 'Lang  is required!']);
            return $this->sendError('Validation Error.', $errors);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $salon_id                                 = $request->salon_id;
        $option                                   =   new SalonOption();
        $lang                                     =  $request->hasHeader('lang') ;
        if($lang == 'ar') {
            $option->service_name_ar              =   $request->service_name;
        }else if($lang == 'tu') {
            $option->service_name_tu              =   $request->service_name;
        }else if($lang == 'de') {
            $option->service_name_de              =   $request->service_name;
        }else {
            $option->service_name_en              =   $request->service_name;
        }

        $option->service_price                 =   $request->service_price;
        $option->service_time                  =   $request->service_time;
        if(  $request->service_time_sign =="min") {
            $option->service_time_sign_en             =  "minute";
            $option->service_time_sign_de             =  "minute";
            $option->service_time_sign_ar             =  "دقيقه";
            $option->service_time_sign_tu             =  "dakika";
        }else {
            $option->service_time_sign_en             =  "hour";
            $option->service_time_sign_de             =  "stunde";
            $option->service_time_sign_ar             =  "ساعه";
            $option->service_time_sign_tu             =  "saat";
        }
        $option->status                               =   $request['status'];
        $option->own                                  =   1;
        $option->salon_id                             =   $salon_id;
        $option->save();

        $success = true;
        return $this->sendResponse($success, "service added successfully");
    }

    public function activeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
            'salon_service_id' => ['required', 'exists:salon_services,id'],
            'service_price' => ['required'],
            'service_time' => ['required'],
            'service_time_sign' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $salon_id  = $request->salon_id;
        $service   = SalonService::find($request->salon_service_id);
        $option   = SalonOption::firstWhere(['salon_service_id' => $request->salon_service_id, 'salon_id' => $request->salon_id]);
        if ($option){
            return $this->sendError('Validation Error.', 'service already activated');
        }

        $option                                =   new SalonOption();
        $option->service_name_ar               =   $service->service_name_ar;
        $option->service_name_en               =   $service->service_name_en;
        $option->service_name_de               =   $service->service_name_de;
        $option->service_name_tu               =   $service->service_name_tu;
        $option->service_price                 =   $request->service_price;
        $option->service_time                  =   $request->service_time;
        if(  $request->service_time_sign =="min") {
            $option->service_time_sign_en             =  "minute";
            $option->service_time_sign_de             =  "minute";
            $option->service_time_sign_ar             =  "دقيقه";
            $option->service_time_sign_tu             =  "dakika";
        }else {
            $option->service_time_sign_en             =  "hour";
            $option->service_time_sign_de             =  "stunde";
            $option->service_time_sign_ar             =  "ساعه";
            $option->service_time_sign_tu             =  "saat";
        }
        $option->status                        =   1;
        $option->own                           =   0;
        $option->salon_id                      =   $salon_id;
        $option->salon_service_id              =   $request->salon_service_id;
        $option->save();

        $success = true;
        return $this->sendResponse($success, "service activated successfully");
    }

    public function inActiveService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
            'salon_service_id' => ['required', 'exists:salon_services,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $option   = SalonOption::firstWhere(['salon_service_id' => $request->salon_service_id, 'salon_id' => $request->salon_id]);
        if ($option){
            $option->delete();
            return $this->sendResponse(true, "service inactivated successfully");
        }
        else{
            return $this->sendError('Validation Error.', 'service already inactive');
        }

    }

    public function updateOwnService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'          => ['required', 'exists:salon_options,id'],
            'service_name'      => ['required', 'string', 'min:3', 'max:50'],
            'service_price'     => ['required'],
            'service_time'      => ['required'],
            'service_time_sign' => ['required'],
        ]);

        if (!$request->hasHeader('lang')) {
            $errors = [];
            array_push($errors, ['lang' => 'Lang  is required!']);
            return $this->sendError('Validation Error.', $errors);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $option                                   =   SalonOption::where('id' , $request->service_id)->first();
        $lang                                     =   $request->hasHeader('lang') ;
        if($lang == 'ar') {
            $option->service_name_ar              =   $request->service_name;
        }else if($lang == 'tu') {
            $option->service_name_tu              =   $request->service_name;
        }else if($lang == 'de') {
            $option->service_name_de              =   $request->service_name;
        }else {
            $option->service_name_en              =   $request->service_name;
        }

        $option->service_price                 =   $request->service_price;
        $option->service_time                  =   $request->service_time;
        if(  $request->service_time_sign =="min") {
            $option->service_time_sign_en             =  "minute";
            $option->service_time_sign_de             =  "minute";
            $option->service_time_sign_ar             =  "دقيقه";
            $option->service_time_sign_tu             =  "dakika";
        }else {
            $option->service_time_sign_en             =  "hour";
            $option->service_time_sign_de             =  "stunde";
            $option->service_time_sign_ar             =  "ساعه";
            $option->service_time_sign_tu             =  "saat";
        }
        $option->status                               =   $request['status'];
        $option->own                                  =   1;
        $option->save();

        $success = true;
        return $this->sendResponse($success, "service updated successfully");
    }

    public function toggleOwnService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'          => ['required', 'exists:salon_options,id'],
            'status'              => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $option                   =   SalonOption::where('id' , $request->service_id)->first();
        $option->status           =   $request['status'];
        $option->save();
        $success = true;
        return $this->sendResponse($success, "data updated successfully");
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'exists:salon_options,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $service = SalonOption::find($request->service_id);
        if($service){
            $service->delete();
            return $this->sendResponse(true, "service deleted successfully");
        }else{
            return $this->sendError('Validation Error.', 'deleting service failed');
        }

    }

}
