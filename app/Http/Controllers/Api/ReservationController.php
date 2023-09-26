<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\SalonReservation;
use App\Models\ReservationOption;
use App\Http\Resources\Reservation                  as ReservationResource;
use App\Http\Requests\Api\StoreReservationRequest;
use App\Http\Requests\Api\UpdateReservationRequest;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Notification;

class ReservationController extends  BaseController {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( StoreReservationRequest $request ) {
        DB::beginTransaction();
        try {
            $validated                            =  $request->validated();
            $validated['barber_id']               =  $request->barber_id;
            $validated['reservation_type']        =  $request->reservation_type;
            $validated['reservation_notice']      =  $request->reservation_notice;
            $validated['user_id']                 =  Auth::guard( 'api' )->user()->id;
            $endTime                              =  strtotime( ''.'+'.$validated[ 'duration' ].'minutes'.'', strtotime( $validated[ 'start_time' ] ) );
            $validated['end_time']                =  date( 'H:i', $endTime );
            $reservation                          =  SalonReservation::create( $validated );
            if ( !empty( $validated[ 'salon_option_id' ] ) ) {
                foreach ( $validated[ 'salon_option_id' ] as $option ) {
                    $reservation_option                               =  new ReservationOption();
                    $reservation_option->salon_reservation_id         =  $reservation->id;
                    $reservation_option->salon_option_id              =  $option;
                    $reservation_option->save();
                }
            }
            DB::commit();
            $success    = null;
            return $this->sendResponse( $success, 'reservation done successfully.' );
        } catch( Exception $e ) {
            DB::rollback();
            return $this->sendError( 'Not Found.', 'something wrong happen' );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( UpdateReservationRequest $request, SalonReservation $reservation ) {
        DB::beginTransaction();
        try {
            $validated                            =  $request->validated();
            $validated['barber_id']               =  $request->barber_id;
            $validated['reservation_type']        =  $request->reservation_type;
            $validated['reservation_notice']      =  $request->reservation_notice;
            $endTime                              = strtotime( ''.'+'.$validated[ 'duration' ].'minutes'.'', strtotime( $validated[ 'start_time' ] ) );
            $validated[ 'end_time' ]              =  date( 'H:i', $endTime );
            $reservation->update( $validated );
            $reservation->ReservationOptions()->delete();
            if ( !empty( $validated[ 'salon_option_id' ] ) ) {
                foreach ( $validated[ 'salon_option_id' ] as $option ) {
                    $reservation_option                               =  new ReservationOption();
                    $reservation_option->salon_reservation_id         =  $reservation->id;
                    $reservation_option->salon_option_id              =  $option;
                    $reservation_option->save();

                }
            }
            DB::commit();
            $success    = null;
            return $this->sendResponse( $success, 'reservation updated successfully.' );
        } catch( Exception $e ) {
            DB::rollback();
            return $this->sendError( 'Not Found.', 'something wrong happen' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        $reservation = SalonReservation::where( 'id', $id )->first();
        if ( !$reservation ) {
            return $this->sendError( 'Not Found .', 'this reservation not found' );

        } else {
            $reservation->ReservationOptions()->delete();
            $reservation->delete();
            if ( !empty( $reservation->user->fcm_token ) ) {
                $SERVER_API_KEY              = 'AAAApY7z2Ao:APA91bEJUvwDz4O9e9-J397A3WkiPDHxebB5tzaGkQDvk6rq3eDhCQvRo9sYnaCh6mFd4Uvo-Qez6anW_LZJzz1yr3NPxaBpyxkgwM3X-JWtaI2c-YhfWIpQq5mR3FL7l4ly7vu3PAaf';
                $data = [
                    'registration_ids'        =>   [ $reservation->user->fcm_token ],
                    'notification'            =>   [
                        'title'               =>     'حذف الحجز ',
                        'body'                =>     'لقد تم حذف الحجز الخاص بك',
                        'sound'               =>     'alert',
                        'content_available'   =>     true,
                        'priority'            =>     'high',
                    ],
                    'data'                    =>   [
                        'click_action'     => 'FLUTTER_NOTIFICATION_CLICK',
                        'type'             => 'Delete'
                    ],
                    //  'to' =>  '/topics/topic',
                ];
                $dataString = json_encode( $data );
                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch, CURLOPT_POST, true );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $dataString );
                $response = curl_exec( $ch );
                //dd( $response );
                $notifcation             = new Notification();
                $notifcation->title      =  'حذف الحجز ';
                $notifcation->details    =   'لقد تم حذف الحجز الخاص بك';
                $notifcation->salon_id   =  $reservation->salon_id;
                $notifcation->user_id    =   $reservation->user_id;
                $notifcation->save();
            }
            $success    = null;

            return $this->sendResponse( $success, 'reservation deleted successfully.' );
        }
    }

    public function all_user_reservations() {
        return ReservationResource::collection( SalonReservation::where( 'user_id', Auth::guard( 'api' )->user()->id )->with( 'salon' )->orderBy( 'id', 'DESC' )->get() );
    }

    public function date_reservation( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'salon_id'                   => 'required',
            'reservation_date'           => 'required',
        ] );
        if ( $validator->fails() ) {
            return $this->sendError( 'Validation Error.', $validator->errors() );
        }
        if ( !empty( $request->barber_id ) ) {
            return ReservationResource::collection( SalonReservation::where( [ 'user_id' => Auth::guard( 'api' )->user()->id, 'salon_id' => $request->salon_id, 'reservation_date' => $request->reservation_date, 'barber_id' => $request->barber_id ] )->get() );
        } else {
            return ReservationResource::collection( SalonReservation::where( [ 'user_id' => Auth::guard( 'api' )->user()->id, 'salon_id' => $request->salon_id, 'reservation_date' => $request->reservation_date ] )->get() );
        }

    }

    public function NotCompletedReservations( Request $request ) {
        //   return ReservationResource::collection( SalonReservation::where( 'user_id',  Auth::guard( 'api' )->user()->id )->where( 'reservation_date', '<=',  date( 'Y-m-d', strtotime( Carbon::now() ) ) )->where( 'client_done', 0 )->get() );
        $time   =  Carbon::now()->addHours( 2 )->format( 'H:i' );
        return  ReservationResource::collection( SalonReservation::where( 'user_id',  Auth::guard( 'api' )->user()->id )->where( 'reservation_date', '<=',  date( 'Y-m-d', strtotime( Carbon::now() ) ) )->whereTime( 'end_time', '<=', $time )->where( 'client_done', 0 )->get() );

    }

}
