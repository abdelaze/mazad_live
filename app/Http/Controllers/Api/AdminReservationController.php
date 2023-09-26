<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\SalonReservation;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Models\Notification;

class AdminReservationController extends BaseController
{

    public function todayReservations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id'  => ['required', 'exists:salons,id']
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $time           =  Carbon::now()->addHours(2)->format('H:i');
        $reservations   = Salon::withCount('todayReservations')->withSum('todayReservations', 'cost')->with(['todayReservations.barber:id,first_name,last_name,phone_number,photo'])->with(['todayReservations.user:id,first_name,last_name,phone_number,photo'])->where('id' , $request->salon_id)->first();
        return $this->sendResponse(true, $reservations);
    }

    public function allReservations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if(!empty($request->start_date) && empty($request->end_date)) {
            $salon  = Salon::where('id', $request->salon_id)->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                $q->whereDate('reservation_date', '>=', $request->start_date);
            })->first();
            if(!empty($salon)) {
                $salon['reservations_count']     = count($salon->reservations);
                $salon['reservations_sum_cost']  = $salon->reservations()->whereDate('reservation_date', '>=', $request->start_date)->sum('cost');
            }else {
                $salon['reservations_count']     = 0;
                $salon['reservations_sum_cost']  = 0;
            }
        }else if(!empty($request->end_date) && empty($request->start_date)) {
            $salon  = Salon::where('id', $request->salon_id)->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                $q->whereDate('reservation_date', '<=', $request->end_date);
            })->first();
            if(!empty($salon)) {
                $salon['reservations_count']     = count($salon->reservations);
                $salon['reservations_sum_cost']  = $salon->reservations()->whereDate('reservation_date', '<=', $request->end_date)->sum('cost');
            }else {
                $salon['reservations_count']     = 0;
                $salon['reservations_sum_cost']  = 0;
            }
        }  else if(!empty($request->end_date) && !empty($request->start_date)) {
            $salon  = Salon::where('id', $request->salon_id)->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                             $q->whereBetween('reservation_date',[$request->start_date , $request->end_date]);
                            })->first();
            if(!empty($salon)) {
                $salon['reservations_count']     = count($salon->reservations);
                $salon['reservations_sum_cost']  = $salon->reservations()->whereBetween('reservation_date', [$request->start_date ,$request->end_date ])->sum('cost');
            }else {
                $salon['reservations_count']     = 0;
                $salon['reservations_sum_cost']  = 0;
            }
        }else {
            $salon  = Salon::where('id', $request->salon_id)->with(['reservations.barber:id,first_name,last_name,phone_number,photo'])->with(['reservations.user:id,first_name,last_name,phone_number,photo'])->with('reservations', function( $q) use($request){
                         $q->where('reservation_date', Carbon::today());
            })->first();
            if(!empty($salon)) {
               $salon['reservations_count']     = count($salon->reservations);
               $salon['reservations_sum_cost']  = $salon->reservations()->where('reservation_date', Carbon::today())->sum('cost');
            }else {
               $salon['reservations_count']     = 0;
               $salon['reservations_sum_cost']  = 0;
            }
        }

       return $this->sendResponse(true, $salon);
    }

    public function getReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => ['required', 'exists:salon_reservations,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $reservation = SalonReservation::where('id', $request->reservation_id)->with(['barber:id,first_name,last_name,phone_number,photo', 'user:id,first_name,last_name,phone_number,photo', 'options:id,salon_service_id,service_price,service_time,service_time_sign_ar,service_time_sign_en,service_time_sign_de,service_time_sign_tu,service_name_ar,service_name_de,service_name_de,service_name_tu','salon'])->first();
        return $this->sendResponse(true, $reservation);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => ['required', 'exists:salon_reservations,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $reservation = SalonReservation::find($request->reservation_id);
        $reservation->ReservationOptions()->delete();
        $reservation->delete();
        if(!empty($reservation->user->fcm_token)) {
            $SERVER_API_KEY              = 'AAAApY7z2Ao:APA91bEJUvwDz4O9e9-J397A3WkiPDHxebB5tzaGkQDvk6rq3eDhCQvRo9sYnaCh6mFd4Uvo-Qez6anW_LZJzz1yr3NPxaBpyxkgwM3X-JWtaI2c-YhfWIpQq5mR3FL7l4ly7vu3PAaf';
            $data = [
               "registration_ids"        =>   [$reservation->user->fcm_token],
               "notification"            =>   [
                   "title"               =>     'حذف الحجز ',
                   "body"                =>     'لقد تم حذف الحجز الخاص بك',
                   "sound"               =>     "alert",
                   "content_available"   =>     true,
                   "priority"            =>     "high",
               ],
               "data"                    =>   [
                                               "click_action"     => "FLUTTER_NOTIFICATION_CLICK",
                                               "type"             => "Delete"
                                             ],
               ];
               $dataString = json_encode($data);
               $headers = [
                   'Authorization: key=' . $SERVER_API_KEY,
                   'Content-Type: application/json',
               ];
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
               curl_setopt($ch, CURLOPT_POST, true);
               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               $response = curl_exec($ch);
               //dd($response);
               $notifcation             =   new Notification();
               $notifcation->title      =   'حذف الحجز ';
               $notifcation->details    =   'لقد تم حذف الحجز الخاص بك';
               $notifcation->salon_id   =   $reservation->salon_id;
               $notifcation->user_id    =   $reservation->user_id;
               $notifcation->save();
        }
        return $this->sendResponse(true, 'reservation deleted successfully.');
    }

}
