<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\BarberWorkingHour;
use App\Models\Rate;
use App\Models\SalonBarber;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User                  as UserResource;
use DB;
use Illuminate\Support\Facades\File;
use Validator;
use Illuminate\Support\Facades\Auth;

class AdminBarberController extends BaseController {
    public function addBarber( Request $request ) {
        DB::beginTransaction();
        $validator = Validator::make( $request->all(), [
            'first_name'       => [ 'required', 'string', 'min:3', 'max:50' ],
            'last_name'        => [ 'required', 'string', 'min:3', 'max:50' ],
            'gender'           => [ 'required' ],
            'phone_number'     => [ 'required', 'unique:users' ],
            'password'         => [ 'required', 'min:6' ],
            'confirm-password' => [ 'required', 'same:password' ],
            'category'         => [ 'required' ],
            'salon_id'         => [ 'required', 'exists:salons,id' ],
            'photo'            => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048' ],
        ] );

        if ( $validator->fails() ) {
            return $this->sendError( 'Validation Error.', $validator->errors() );
        }

        $requestArray = ( array ) $request->all();

        $requestArray[ 'user_name' ]      =  $this->generateRandomString();
        $requestArray[ 'type' ]           =  'barber';
        $requestArray[ 'isVerified' ]     =  1;
        $requestArray[ 'password' ]       =  bcrypt( $request->password );
        if ( $request->photo ) {
            $path = public_path( 'uploads/barbers/' );
            if ( !file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }

            $file = $request->file( 'photo' );
            $fileName = uniqid() . '_' . trim( $file->getClientOriginalName() );
            $requestArray[ 'photo' ]    = $fileName;
            $file->move( $path, $fileName );
        }
        $user                           =  User::create( $requestArray );
        if ( $user ) {
            $salon_barber = new SalonBarber();
            $salon_barber->user_id      =  $user->id;
            $salon_barber->salon_id     =  $request->salon_id;
            $salon_barber->category     =  json_decode($request->category);
            $salon_barber->save();

            $working_hours = [
                [
                    'day'        => 'SUN',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day'        => 'MON',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day'        => 'TUE',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day'        => 'WED',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day'        => 'THUR',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day'        => 'FRI',
                    'start_time' => '00:00',
                    'end_time'   => '00:00',
                    'status'     => 1
                ],

                [
                    'day' => 'SAT',
                    'start_time' => '00:00',
                    'end_time' => '00:00',
                    'status'   => 1
                ],
            ];
            foreach ( $working_hours as  $working_hour ) {
                $work_hour                   =   new  BarberWorkingHour();
                $work_hour->day              =   $working_hour[ 'day' ];
                $work_hour->opening          =   $working_hour[ 'start_time' ];
                $work_hour->closing          =   $working_hour[ 'end_time' ];
                $work_hour->status           =   ( isset( $working_hour[ 'status' ] ) ) ? 1 : 0;
                $work_hour->salon_id         =   $request->salon_id;
                $work_hour->user_id          =   $user->id;
                $work_hour->save();
            }
            DB::commit();
            $success = true;
            return $this->sendResponse( $success, 'barber added successfully' );
        }
    }

    public function updateBarber( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'barber_id'        => [ 'required', 'exists:users,id' ],
            'first_name'       => [ 'required', 'string', 'min:3', 'max:50' ],
            'last_name'        => [ 'required', 'string', 'min:3', 'max:50' ],
            'gender'           => [ 'required' ],
            'phone_number'     => 'required|unique:users,phone_number,'.$request->barber_id,
            'password'         => [ 'nullable', 'min:6' ],
            'confirm-password' => [ 'nullable', 'same:password' ],
            'category'         => [ 'required' ],
            'salon_id'         => [ 'required', 'exists:salons,id' ],
            'photo'            => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048' ],
        ] );

        if ( $validator->fails() ) {
            return $this->sendError( 'Validation Error.', $validator->errors() );
        }

        $barber = User::firstWhere( [ 'id' => $request->barber_id, 'type' => 'barber' ] );

        $requestArray = ( array ) $request->all();
        if ( !empty( $request->password ) ) {
            $requestArray[ 'password' ]    =  bcrypt( $request->password );
        }

        if ( $barber ) {
            if ( $request->photo ) {
                $path = public_path( 'uploads/barbers/' );
                if ( !file_exists( $path ) ) {
                    mkdir( $path, 0777, true );
                }
                File::delete( $path . $barber->photo );
                $file = $request->file( 'photo' );
                $fileName = uniqid() . '_' . trim( $file->getClientOriginalName() );
                $requestArray[ 'photo' ] = $fileName;
                $file->move( $path, $fileName );
            }
            $barber->update( $requestArray );
            $salon_barber                =  SalonBarber::where( 'user_id', $barber->id )->first();
            $salon_barber->salon_id      =  $request->salon_id;
            $salon_barber->category      =  json_decode($request->category);
            $salon_barber->save();
            return $this->sendResponse( true, 'barber updated successfully' );
        } else {
            return $this->sendError( 'Validation Error.', 'barber dosn\'t exist');
        }
    }

    public function getBarber(Request $request) {
        $barber = User::where(['id' => Auth::guard('api')->user()->id , 'type' => 'barber'])->with('working_hours:id, salon_id, user_id, day, opening, closing, status')->first();
        return $this->sendResponse(true, $barber);
    }

    public function allBarbers(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $salon_id    = $request->salon_id;
        $barber_ids  =  SalonBarber::where('salon_id' ,$salon_id)->pluck('user_id');
        $salon       =  Salon::where('id' , $salon_id)->with(['country' , 'state'])->get(['id','address']);
        $barbers     = UserResource::collection(User::whereIn('id' ,$barber_ids)->with(['working_hours','category','reservations','rates'])->get());
        return $this->sendResponse( $salon , $barbers);
    }

    public function barberTodayAppointments(Request $request) {
        $validator = Validator::make($request->all(), [
            'barber_id' => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $barber_id    = $request->barber_id;
        $appointments = BarberWorkingHour::where(['user_id' => $barber_id, 'day' => date('D')])->orderBy('id', 'DESC')->first();
        $success      = true;
        return $this->sendResponse($success, $appointments);
    }

    public function barberAllAppointments(Request $request) {
        $validator = Validator::make($request->all(), [
            'barber_id' => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $barber_id    = $request->barber_id;
        $appointments = BarberWorkingHour::where(['user_id' => $barber_id])->get();
        $success      = true;
        return $this->sendResponse($success, $appointments);
    }

    public function getBarberRates(Request $request) {
        $validator = Validator::make($request->all(), [
            'barber_id' => ['required', 'exists:users, id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $barber_id        = $request->barber_id;
        $rates            = User::where('id', $barber_id)->with('rates')->first();
        $rateCount        = Rate::where('barber_id', $barber_id)->count();
        $rates->rateCount = $rateCount;
        return $this->sendResponse(true, $rates);
    }

    function generateRandomString($length = 10) {
        $characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength  = strlen($characters);
        $randomString      = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function updateworkHours(Request $request) {

        $validator = Validator::make($request->all(), [
            'salon_id'  =>  ['required', 'exists:salons,id'],
            'barber_id' => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $working_hours                                   =    $request->all_working_hours;
        if(!empty($working_hours)) {
            foreach( $working_hours as  $working_hour) {
                $working_hourr                           =   BarberWorkingHour::where('id',$working_hour['id'])->where('salon_id' , $request->salon_id)->where('user_id' , $request->barber_id)->first();
                if(!empty($working_hourr)) {
                    $working_hourr->day                  =   $working_hour['day'];
                    $working_hourr->opening              =   $working_hour['start_time'];
                    $working_hourr->closing              =   $working_hour['end_time'];
                    $working_hourr->status               =   (isset($working_hour['status'])) ? $working_hour['status'] : 0;
                    $working_hourr->salon_id             =   $request->salon_id;
                    $working_hourr->user_id              =   $request->barber_id;
                    $working_hourr->save();
                } else {
                    $working_hourr                       =   BarberWorkingHour::where('day',$working_hour['day'])->where('salon_id' , $request->salon_id)->where('user_id' , $request->barber_id)->first();
                    if(empty($working_hourr)) {
                        $work_hour                       =   new BarberWorkingHour();
                        $work_hour->day                  =   $working_hour['day'];
                        $work_hour->opening              =   $working_hour['start_time'];
                        $work_hour->closing              =   $working_hour['end_time'];
                        $work_hour->status               =   (isset($working_hour['status'])) ? $working_hour['status'] : 0;
                        $work_hour->salon_id             =   $request->salon_id;
                        $work_hour->user_id              =   $request->barber_id;
                        $work_hour->save();
                    }
                }
            }
        }
        $success    = null;
        return $this->sendResponse($success, 'your work hours updated successfully.' );
        }

    }
