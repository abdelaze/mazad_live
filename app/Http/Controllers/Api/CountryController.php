<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class CountryController extends BaseController
{
    public function index() {
        $countries   =  Country::where('status',1)->select('id','name_ar','name_en')->get();
        return $this->sendResponse( $countries , trans('messages.data_returned_successfully'));
    }

    public function getStates(Request $request) {
        $validator = Validator::make($request->all(), [
            'country_id'            => 'required|exists:countries,id',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $states   = State::where('country_id' , $request->country_id)->select('id','name_ar','name_en')->get();
        return $this->sendResponse(  $states , trans('messages.data_returned_successfully'));
    }

    public function getStateCities(Request $request) {
        $validator = Validator::make($request->all(), [
            'state_id'            => 'required|exists:states,id',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $cities   = City::where('state_id' , $request->state_id)->select('id','name_ar','name_en')->get();
        return $this->sendResponse( $cities , trans('messages.data_returned_successfully'));
    }
}
