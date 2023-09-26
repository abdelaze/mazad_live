<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\SalonReservation;
use App\Models\SalonBarber;
use App\Models\Salon;
use App\Http\Resources\Reservation          as ReservationResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;



class AdminBarberReservationController extends BaseController
{
    public function todayReservations(Request $request)
    {
        $salon_id     = SalonBarber::where('user_id' , $request->barber_id)->pluck('salon_id');
        if(!empty($salon_id[0])) {
            $time           =  Carbon::now()->addHours(2)->format('H:i');
            $reservations   =  Salon::withCount('todayReservations')->withSum('todayReservations', 'cost')->whereHas('todayReservations',function($q){
                                                $q->where('barber_id' , Auth::guard('api')->user()->id);
                                     })->with(['todayReservations.barber:id,first_name,last_name'])->with(['todayReservations.user:id,first_name,last_name,photo'])->where(['id' => $salon_id[0]])->first();
            return $this->sendResponse(true, $reservations);
        }
    }

    public function allReservations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barber_id'     =>  ['required', 'exists:users,id'],
            'start_date'    => 'date_format:Y-m-d',
            'end_date'      =>   'date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $salon_id  = SalonBarber::where('user_id' , $request->barber_id)->pluck('salon_id');
        if(!empty($salon_id[0])) {
            if(!empty($request->start_date) && empty($request->end_date)) {
                $salon  = Salon::where('id', $salon_id[0])->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                    $q->whereDate('reservation_date', '>=', $request->start_date)->where('barber_id' , $request->barber_id);
                })->first();
                if(!empty($salon)) {
                    $salon['reservations_count']     = count($salon->reservations);
                    $salon['reservations_sum_cost']  = $salon->reservations()->whereDate('reservation_date', '>=', $request->start_date)->where('barber_id' , $request->barber_id)->sum('cost');
                }else {
                    $salon['reservations_count']     = 0;
                    $salon['reservations_sum_cost']  = 0;
                }
            }else if(!empty($request->end_date) && empty($request->start_date)) {
                $salon  = Salon::where('id', $salon_id[0])->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                    $q->whereDate('reservation_date', '<=', $request->end_date)->where('barber_id' , $request->barber_id);
                })->first();
                if(!empty($salon)) {
                    $salon['reservations_count']     = count($salon->reservations);
                    $salon['reservations_sum_cost']  = $salon->reservations()->whereDate('reservation_date', '<=', $request->end_date)->where('barber_id' , $request->barber_id)->sum('cost');
                }else {
                    $salon['reservations_count']     = 0;
                    $salon['reservations_sum_cost']  = 0;
                }
            }  else if(!empty($request->end_date) && !empty($request->start_date)) {
                $salon  = Salon::where('id', $salon_id[0])->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                                $q->whereBetween('reservation_date',[$request->start_date , $request->end_date])->where('barber_id' , $request->barber_id);
                                })->first();
                if(!empty($salon)) {
                    $salon['reservations_count']     = count($salon->reservations);
                    $salon['reservations_sum_cost']  = $salon->reservations()->whereBetween('reservation_date', [$request->start_date ,$request->end_date ])->where('barber_id' , $request->barber_id)->sum('cost');
                }else {
                    $salon['reservations_count']     = 0;
                    $salon['reservations_sum_cost']  = 0;
                }
            }else {
                $salon  = Salon::where('id', $salon_id[0])->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                            $q->where('reservation_date', Carbon::today())->where('barber_id' , $request->barber_id);
                })->first();
                if(!empty($salon)) {
                $salon['reservations_count']     = count($salon->reservations);
                $salon['reservations_sum_cost']  = $salon->reservations()->where('reservation_date', Carbon::today())->where('barber_id' , $request->barber_id)->sum('cost');
                }else {
                $salon['reservations_count']     = 0;
                $salon['reservations_sum_cost']  = 0;
                }
            }

        return $this->sendResponse(true, $salon);
        }
    }

    public function getReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => ['required', 'exists:salon_reservations,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $reservation = SalonReservation::where('id', $request->reservation_id)->with(['barber:id,first_name,last_name,photo', 'user:id,first_name,last_name,photo', 'options:id,salon_service_id,service_price,service_time,service_time_sign_ar,service_time_sign_en,service_time_sign_de,service_time_sign_tu,service_name_ar,service_name_de,service_name_de,service_name_tu'])->first();
        return $this->sendResponse(true, $reservation);
    }

    public function CleintAttend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => ['required', 'exists:salon_reservations,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $reservation = SalonReservation::where('id', $request->reservation_id)->first();
        if(!empty($reservation)) {
            $reservation->barber_done    = 1;
            $reservation->barber_attend  = $request->barber_attend;
            $reservation->save();
        }
        return $this->sendResponse(true, 'data updates successfully');
    }

    public function NotCompletedReservations(Request $request) {
        return ReservationResource::collection(SalonReservation::where('barber_id' ,  Auth::guard('api')->user()->id)->where('reservation_date' ,'<',  date('Y-m-d' , strtotime(Carbon::now())))->where('barber_done' , 0 )->get());
    }

}
