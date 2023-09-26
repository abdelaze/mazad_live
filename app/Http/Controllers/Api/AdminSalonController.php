<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Rate;
use App\Models\Salon;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\SalonSelectedType;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\File;
use Validator;
use Carbon\Carbon;
use App\Http\Resources\Salone  as SaloneResource;
use Illuminate\Support\Facades\Auth;

class AdminSalonController extends BaseController
{
    public function addSalon(Request $request)
    {

        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'user_id'     => ['required', 'exists:users,id'],
            'country_id'  => ['required', 'exists:countries,id'],
            'state_id'    => ['required', 'exists:states,id'],
            'name'        => ['required', 'string', 'min:4', 'max:50'],
            'address'     => ['required', 'string', 'min:4', 'max:50'],
            'description' => ['nullable', 'string', 'min:4', 'max:500'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'types'       => ['required', 'array','min:1', 'max:3'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $formInput              = $request->all();
        $formInput['user_id']   = $request->user_id;
        $country     =  Country::where('id', $formInput['country_id'])->select('name')->first();
        $state       =  State::where('id', $formInput['state_id'])->select('name')->first();
        $client      =  new \GuzzleHttp\Client();
        $x           =  $client->get('https://maps.googleapis.com/maps/api/geocode/json?address='.$country->name.','.$state->name.','. $formInput['address'].'&key=AIzaSyDKAMQo1g2nMZZmu4xuawfX3CpzmIeDHiU');
        $z           =  json_decode($x->getBody()->getContents(),true);

        if(!empty( $z['results'][0])) {
            $formInput['lat'] = $z['results'][0]['geometry']['location']['lat'];
            $formInput['lng'] = $z['results'][0]['geometry']['location']['lng'];
        }
        if($request->photo){
            $path = public_path('uploads/salones/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('photo');
            $fileName = uniqid() . '_' . trim($file->getClientOriginalName());

            $formInput['photo'] = $fileName;
            $file->move($path, $fileName);
        }

        $salon = Salon::create($formInput);
        //return $this->sendError($formInput);
        foreach($formInput['types'] as $type){
            $insert_type = [
                'salon_type_id' => $type,
                'salon_id'      => $salon->getKey()
            ];
            SalonSelectedType::create($insert_type);
        }
        $working_hours = [
            [
               "day"        => "SUN",
               "start_time" => "00:00",
               "end_time"   => "00:00",
               "status"     => 1
            ],

            [
                "day"        => "MON",
                "start_time" => "00:00",
                "end_time"   => "00:00",
                "status"     => 1
             ],

             [
                "day"        => "TUE",
                "start_time" => "00:00",
                "end_time"   => "00:00",
                "status"     => 1
             ],

             [
                "day"        => "WED",
                "start_time" => "00:00",
                "end_time"   => "00:00",
                "status"     => 1
             ],

             [
                "day"         => "THUR",
                "start_time"  => "00:00",
                "end_time"    => "00:00",
                "status"      => 1
             ],

             [
                "day"        => "FRI",
                "start_time" => "00:00",
                "end_time"   => "00:00",
                "status"     => 1
             ],

             [
                "day"        => "SAT",
                "start_time" => "00:00",
                "end_time"   => "00:00",
                "status"     => 1
             ],
       ];

        foreach( $working_hours as  $working_hour) {
            $work_hour                   =   new  WorkingHour();
            $work_hour->day              =   $working_hour['day'];
            $work_hour->opening          =   $working_hour['start_time'];
            $work_hour->closing          =   $working_hour['end_time'];
            $work_hour->status           =   $working_hour['status'];
            $work_hour->salon_id         =   $salon->id;
            $work_hour->save();
        }
        DB::commit();
        $success    = true;
        //return $this->sendResponse($success, "salon added successfully");
        return $this->sendResponse($salon , 'salon added successfully.');
    }

    public function salonTodayAppointments(Request $request){
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $salon_id= $request->salon_id;
        $appointments = WorkingHour::select('closing', 'opening')->where(['salon_id' => $salon_id, 'day' => date('D')])->orderBy('id', 'DESC')->first();
        $success    = true;
        return $this->sendResponse($success, $appointments);
    }

    public function updateSalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id'     => ['required', 'exists:salons,id'],
            'name'         => ['required', 'string', 'min:4', 'max:50'],
            'country_id'   => ['required', 'exists:countries,id'],
            'state_id'     => ['required', 'exists:states,id'],
            'address'      => ['required', 'string', 'min:4', 'max:50'],
            'description'  => ['nullable', 'string', 'min:4', 'max:500'],
            'photo'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'types'        => ['required', 'array','min:1', 'max:3'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $formInput = $request->all();
        $salon = Salon::find($request->salon_id);
        if($request->photo && !empty($request->photo)){
            $path = public_path('uploads/salones/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            File::delete($path . $salon->photo);
            $file = $request->file('photo');
            $fileName = uniqid() . '_' . trim($file->getClientOriginalName());
            $formInput['photo'] = $fileName;
            $file->move($path, $fileName);
        }
        $salon->update($formInput);
        SalonSelectedType::where('salon_id',$request->salon_id)->delete();
        foreach($formInput['types'] as $type) {
            $insert_type = [
                'salon_type_id' => $type,
                'salon_id'      =>$request->salon_id,
            ];
            SalonSelectedType::create($insert_type);
        }

        $success    = true;
        return $this->sendResponse($success, "salon updated successfully");
    }

    public function getSalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $salon            = Salon::where('id' , $request->salon_id)->with('salon_services')->first();
        $rateCount        = Rate::where('salon_id', $request->salon_id)->count();
        $salon->rateCount = $rateCount;
        $workingHours = Salon::where('id' , $request->salon_id)->with('working_hours', function ($query) {
            $query->where('day',strtoupper(date('D')));
        })->first();

        $mytime = Carbon::now();
        $currentTime = $mytime->format('H:i');

        if(!empty($workingHours->working_hours) && count($workingHours->working_hours) > 0){
            if($currentTime >= $workingHours->working_hours[0]->opening && $currentTime <= $workingHours->working_hours[0]->closing){
                $salon->salonStatus = "open";
            }else{
                $salon->salonStatus = "closed";
            }
        }

        return $this->sendResponse(true, $salon);
    }

    public function getSalonRates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $salon_id = $request->salon_id;
        $rates = Salon::where('id', $salon_id)->with('rates')->first();
        $rateCount = Rate::where('salon_id', $request->salon_id)->count();
        $rates->rateCount = $rateCount;
        return $this->sendResponse(true, $rates);
    }
    public function getAdminSalons() {
        return SaloneResource::collection(Salon::withCount('rates')->with('salon_working_hours:id,day,opening,closing,salon_id,status')->with(['country' , 'state' , 'salonTypes'])->where('user_id' , Auth::guard('api')->user()->id)->get());
    }

    public function updateworkHours(Request $request) {

        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $salone   = Salon::where('id' , $request->salon_id)->first();
      //  !empty($salone->working_hours()) ? $salone->working_hours()->delete() : '';
        $working_hours = $request->work_hours;
        if(!empty($working_hours)) {
            foreach( $working_hours as  $working_hour) {

                $working_hourr                       =   WorkingHour::where('id',$working_hour['id'])->where('salon_id' , $request->salon_id)->first();
                if(!empty($working_hourr)) {
                    $working_hourr->day              =   $working_hour['day'];
                    $working_hourr->opening          =   $working_hour['start_time'];
                    $working_hourr->closing          =   $working_hour['end_time'];
                    $working_hourr->status           =   (isset($working_hour['status'])) ? $working_hour['status'] : 0;
                    $working_hourr->salon_id         =   $salone->id;
                    $working_hourr->save();
                }else {
                    $working_hourr                   =   WorkingHour::where('day',$working_hour['day'])->where('salon_id' , $request->salon_id)->first();
                    if(empty($working_hourr)) {
                        $work_hour                       =   new  WorkingHour();
                        $work_hour->day                  =   $working_hour['day'];
                        $work_hour->opening              =   $working_hour['start_time'];
                        $work_hour->closing              =   $working_hour['end_time'];
                        $work_hour->status               =   (isset($working_hour['status'])) ? $working_hour['status'] : 0;
                        $work_hour->salon_id             =   $salone->id;
                        $work_hour->save();
                    }
                }
            }
        }

        $success    = null;
        return $this->sendResponse($success, 'Salon work hours updated successfully.');
    }

    public function deleteBarber(Request $request) {

        $validator = Validator::make($request->all(), [
            'barber_id'  => ['required', 'exists:users,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $id      = $request->barber_id;
        $user    = User::where('id' , $id)->first();
        if($user) {
           $user->delete();
           $success    = null;
           return $this->sendResponse($success, 'barber deleted successfully.');
        }
     }

     public function getAllCountries() {
        $countries = Country::all();
        return $this->sendResponse(true, $countries);
     }

     public function getCountryState($country_id) {
        $states = State::where('country_id' , $country_id)->get();
        return $this->sendResponse(true, $states);
     }
}
