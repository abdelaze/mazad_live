<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\Salone  as SaloneResource;
use App\Http\Resources\SaloneTypes as SaloneTypesResource;
use App\Models\Salon;
use App\Models\SalonSelectedType;
use App\Models\SalonType;
use App\Models\User;
use App\Models\SalonBarber;
use App\Models\WorkingHour;
use App\Models\SalonService;
use App\Models\SalonOption;
use App\Models\SalonReservation;
use App\Models\Favorite;
use App\Models\Rate;
use App\Models\Offer;
use App\Http\Resources\Favorite              as FavoriteResource;
use App\Http\Resources\Rate                  as RateResource;
use App\Http\Resources\SalonService          as SalonServiceResource;
use App\Http\Resources\SalonOption           as SalonOptionResource;
use App\Http\Resources\User                  as UserResource;
use App\Http\Resources\OfferResource;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class SalonController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except('index' , 'show' , 'salone_types' ,'all_services' ,'work_hours','own_services' ,'all_category_salons' , 'all_category_barbers', 'search', 'salonRates','salonOffers');
    }


    public function index(Request $request)
    {
        //return SaloneResource::collection(Salon::all());
        try {
            $validator = Validator::make($request->all(), [
                'lat' => 'required',
                'lng' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $lat   = $request->lat;
            $lon   = $request->lng;
           return SaloneResource::collection(Salon::select("salons.*"
           ,DB::raw("6371 * acos(cos(radians(" . $lat . "))
           * cos(radians(salons.lat))
           * cos(radians(salons.lng) - radians(" . $lon . "))
           + sin(radians(" .$lat. "))
           * sin(radians(salons.lat))) AS distance"))
           ->orderBy("distance","asc")
           ->whereHas('user.packages',function($q){
                      $q->where('status' , 1)->where('end_date'  ,'>=', date('Y-m-d' , strtotime(Carbon::now())));
            })->with('user.packages')
           ->get());
        }catch(Exception $e) {
            return $this->sendError('Error.', 'someThing wrong happen');
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
        return SaloneResource::collection(Salon::withCount('rates')->with('salon_working_hours:id,day,opening,closing,salon_id,status')->with(['country' , 'state' , 'salonTypes'])->where('id' , $id)->get());
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

    public function salone_types(SalonType $type) {
        return SaloneTypesResource::collection($type->get());
    }

    public function addToFavorites(Request $request) {

        $validator = Validator::make($request->all(), [
            'salon_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $f =  Favorite::where('salon_id', $request->salon_id)->where('user_id', Auth::guard('api')->user()->id)->first();

        if(!empty($f)) {
            $f->forceDelete();
            $success    = false;
            return $this->sendResponse($success, 'salon removed from your favorite.');
        } else {
           $data = new Favorite();
           $data->salon_id = $request->salon_id;
           $data->user_id = Auth::guard('api')->user()->id;
           $data->save();
           $success    = true;
           return $this->sendResponse($success, 'salon addeded to your favorites successfully.');
        }
    }

    public function RemoveFavorites(Request $request) {

        $validator = Validator::make($request->all(), [
            'salon_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $f =  Favorite::where('salon_id', $request->salon_id)->where('user_id', Auth::guard('api')->user()->id)->first();

        if(!empty($f)) {
            $f->forceDelete();
            $success    = null;
            return $this->sendResponse($success, 'salon deleted from your favorite.');
        } else {
            return $this->sendError('Not Found.', 'this salon not in your favorites');
        }
    }

    public function UserFavorites() {
        return FavoriteResource::collection(Favorite::where('user_id',Auth::guard('api')->user()->id)->with('salon')->paginate(10));
    }

    public function addToRates(Request $request) {

        $validator = Validator::make($request->all(), [
            'salon_id'          => 'required',
            'rate'              => 'required',
            'barber_rate'       => 'required',
            'reservation_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $rate = Rate::where('salon_id',$request->salon_id)->where('user_id',Auth::guard('api')->user()->id)->first();
        $data = new Rate();
        $data->salon_id      = $request->salon_id;
        $data->barber_id     = $request->barber_id;
        $data->user_id       = Auth::guard('api')->user()->id;
        $data->rate          = $request->rate;
        $data->barber_rate   = $request->barber_rate;
        $data->comment       = $request->comment;
        $data->save();

        if(!empty($request->reservation_id)) {
            $reservation = SalonReservation::where('id' , $request->reservation_id)->first();
            if(!empty($reservation)) {
                $reservation->client_done    = 1;
                $reservation->client_attend  = $request->client_attend;
                $reservation->save();
            }
        }
        $success    = null;
        return $this->sendResponse($success, 'rate addeded successfully.');
    }

    public function all_services ($salone_id) {
        $data['services']     =   SalonServiceResource::collection(SalonOption::where('salon_id' , $salone_id)->with('service:id,service_name_ar,service_name_en,service_name_de,service_name_tu')->where('own',0)->get());
        $data['own_services'] =   SalonOptionResource::collection(SalonOption::where('salon_id' , $salone_id)->where('own',1)->get());
        return $this->sendResponse($data , 'data returnd successfully.');
    }

    public function own_services ($salone_id) {
        return SalonOptionResource::collection(SalonOption::where('salon_id' , $salone_id)->where('own',1)->where('status' , 1)->get());
     }

     public function work_hours ($salone_id) {

          $d          = Carbon::now()->dayOfWeek;
          $days       = $this->dayofWeeks($d);
          foreach($days as $day) {
            $work_hours = WorkingHour::where(['day' => $day , 'salon_id' => $salone_id])->get();
            if(!empty($work_hours[0])) {
               $x[] =  $work_hours[0];
            }
          }
          return $this->sendResponse($x , 200);
    }

    public function all_category_salons (Request $request) {

            $validator = Validator::make($request->all(), [
                'lat'     => 'required',
                'lng'     => 'required',
                'cat_id'  => 'required',

            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $lat    = $request->lat;
            $lon    = $request->lng;
            $cat_id = $request->cat_id;
            if(!empty($cat_id)) {
               $salon_ids   =  SalonSelectedType::where('salon_type_id',$cat_id)->pluck('salon_id');
                if(!empty( $salon_ids)) {
                //return SaloneResource::collection(Salon::whereIn('id' , $salon_ids)->get());
                    return SaloneResource::collection(Salon::select("salons.*"
                    ,DB::raw("6371 * acos(cos(radians(" . $lat . "))
                    * cos(radians(salons.lat))
                    * cos(radians(salons.lng) - radians(" . $lon . "))
                    + sin(radians(" .$lat. "))
                    * sin(radians(salons.lat))) AS distance"))
                    ->orderBy("distance","asc")
                    ->whereIn('id' , $salon_ids)
                    ->whereHas('user.packages',function($q){
                              $q->where('status' , 1)->where('end_date'  ,'>=', date('Y-m-d' , strtotime(Carbon::now())));
                     })->with('user.packages')
                    ->get());
                }
            }else {
                //return SaloneResource::collection(Salon::all());
                return SaloneResource::collection(Salon::select("salons.*"
                        ,DB::raw("6371 * acos(cos(radians(" . $lat . "))
                        * cos(radians(salons.lat))
                        * cos(radians(salons.lng) - radians(" . $lon . "))
                        + sin(radians(" .$lat. "))
                        * sin(radians(salons.lat))) AS distance"))
                        ->orderBy("distance","asc")
                        ->whereHas('user.packages',function($q){
                            $q->where('status' , 1)->where('end_date'  ,'>=', date('Y-m-d' , strtotime(Carbon::now())));
                        })->with('user.packages')->get());
            }
    }

    public function all_category_barbers($salon_id , $cat_id) {
        if($cat_id == 'all') {
            $barber_ids  =  SalonBarber::where('salon_id' ,$salon_id)->pluck('user_id');
            return UserResource::collection(User::whereIn('id' ,$barber_ids)->with('working_hours')->get());
        }else {
            $barber_ids  =  SalonBarber::whereJsonContains('category', (int)$cat_id)->where('salon_id' ,$salon_id)->pluck('user_id');
            return UserResource::collection(User::whereIn('id' ,$barber_ids)->with('working_hours:id,day,opening,closing,user_id,status')->get());
        }
     }

     public function search(Request $request ,Salon $salon) {
          $salon_ids        =  SalonSelectedType::where('salon_type_id',$request->cat_id)->pluck('salon_id');
          if(!empty($salon_ids)) {
              if(isset($request->name)) {
                return SaloneResource::collection(Salon::where(function($query) use($request , $salon_ids) {
                    $query->where('name' ,'like', '%'.$request->name.'%')->whereIn('id' , $salon_ids);
                })->get());
              }else {
                return SaloneResource::collection(Salon::where(function($query) use($request , $salon_ids) {
                    $query->where('address','like', '%'.$request->address.'%')->whereIn('id' , $salon_ids);
                })->get());
              }
         }
     }
    public function salonRates(Request $request) {
        $validator = Validator::make($request->all(), [
            'salon_id'          => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        return RateResource::collection(Rate::with('user')->where('salon_id' , $request->salon_id)->get());
     }
    public function salonOffers(Request $request) {

        if(!empty($request->salon_id)) {
            // ->where('end_date' ,'>=',  date('Y-m-d' , strtotime(Carbon::now())))
            return OfferResource::collection(Offer::with('salon')->where('salon_id' , $request->salon_id)->orderBy('end_date','DESC')->get());
        } else {
            // ->where('end_date' ,'>=',  date('Y-m-d' , strtotime(Carbon::now())))
            return OfferResource::collection(Offer::with('salon')->orderBy('end_date','DESC')->get());
        }
    }

    public function dayofWeeks($day) {
         switch($day) {

            case 0 :
                    return  [  0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED',  4 => 'THUR',  5 => 'FRI', 6 => 'SAT'];
                    break;
            case 1 :
                    return  [  0 => 'MON', 1 => 'TUE', 2 => 'WED',  3 => 'THUR',  4 => 'FRI', 5 => 'SAT' , 6 => 'SUN'];
                    break;
            case 2 :
                    return  [  0 => 'TUE' , 1 => 'WED',  2 => 'THUR',  3 => 'FRI', 4 => 'SAT' , 5 => 'SUN' , 6 => 'MON'];
                    break;
            case 3 :
                    return  [  0 => 'WED' , 1 => 'THUR',  2 => 'FRI', 3 => 'SAT' , 4 => 'SUN' , 5 => 'MON', 6 => 'TUE'];
                    break;
            case 4 :
                    return  [  0 => 'THUR',  1 => 'FRI', 2 => 'SAT' , 3 => 'SUN' , 4 => 'MON', 5 => 'TUE', 6 => 'WED'];
                    break;
            case 5 :
                    return  [  0 => 'FRI', 1 => 'SAT' , 2 => 'SUN' , 3 => 'MON', 4 => 'TUE', 5 => 'WED' ,  6 => 'THUR'];
                    break;
            case 6 :
                    return  [  0 => 'SAT' , 1 => 'SUN' , 2 => 'MON', 3 => 'TUE', 4 => 'WED' ,  5 => 'THUR' , 6 => 'FRI'];
                    break;
            default :
                   return  [  0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED',  4 => 'THUR',  5 => 'FRI', 6 => 'SAT'];
                   break;
         }

    }
}
