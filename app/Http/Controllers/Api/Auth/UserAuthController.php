<?php

namespace App\Http\Controllers\Api\Auth;
use DB;
use Validator;
use App\Models\Rate;
use App\Models\User;
use App\Models\SalonBarber;
use App\Mail\VerifyCodeMail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Pnlinh\InfobipSms\Facades\InfobipSms;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\Notification        as NotificationResource;

class UserAuthController extends BaseController
{

    /**
     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request) {

        //var_dump($response);
        DB::beginTransaction();
        try {
        $validator = Validator::make($request->all(), [
            'full_name'            => 'required',
            'user_name'            => ['required',Rule::unique('users')->where(function ($query) use ($request) { return $query->where('isVerified', 1); })],
            'phone_number'         => ['required',Rule::unique('users')->where(function ($query) use ($request) { return $query->where('isVerified', 1); })],
            "email"                => ['required','email',Rule::unique('users')->where(function ($query) use ($request) { return $query->where('isVerified', 1); })],
            'password'             => 'required|min:6',
            'c_password'           => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $input                 =  $request->all();
        $user1                 =  User::where('phone_number' , $input['phone_number'])->where('isVerified' , 0)->first();
        if($user1) {
             $user1->delete();
        }
        $input['password']     = bcrypt($input['password']);
        $user                  =  User::create($input);
        if($user) {
            try {
                $otp           = '1234';//rand(1000, 9999);
                DB::table('phone_verifications')->updateOrInsert(['phone' => $request['phone_number']],
                [
                'token'      => $otp,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
                $message      = str_replace("#OTP#", $otp, 'Your otp is #OTP#.');
                /*$response     = InfobipSms::send($input['phone_number'],  $message);*/
                Mail::to($user->email)->send( new VerifyCodeMail($message));
                $success      = null;
                DB::commit();
                return $this->sendResponse($success, trans('messages.verfication_code_sended_successfully.'));
            } catch (\Exception $e) {
                dd($e);
                DB::rollback();
                return $this->sendError('Verfication Error.', trans('messages.faild_to_send_sms.'));
            }
        }
        }catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }
    }
    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request) {

        if(Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password ])){
            $user                     =  Auth::user();
            if($user->isVerified ==  0) {
                $success   = null;
                return $this->sendResponse($success , trans('messages.verify_your_account_first'));
            }else{
                $user['token']            =  $user->createToken('MyApp')-> accessToken;
                return $this->sendResponse( $user, trans('messages.user_login_successfully'));
            }

        }else {
            $success   = null;
            return $this->sendError(trans('messages.Unauthorised') , $success);
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'       => 'required',
            'otp'         => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $user = User::where('phone_number', $request->phone)->first();
        if($user)
        {
            if($user->isVerified == 1)
            {
                $user['token'] =  $user->createToken('MyApp')-> accessToken;
                return $this->sendResponse($user, trans('messages.user_login_successfully'));
            }
            $data = DB::table('phone_verifications')->where([
                'phone' => $request['phone'],
                'token' => $request['otp'],
            ])->first();

            if($data)
            {
                DB::table('phone_verifications')->where([
                    'phone' => $request['phone'],
                    'token' => $request['otp'],
                ])->delete();

                $user->isVerified = 1;
                $user->save();
                $user['token']    =  $user->createToken('MyApp')-> accessToken;

               return $this->sendResponse($user,trans('messages.user_login_successfully'));
            }
            else{
                return $this->sendError('Not matched.', trans('messages.phone_number_and_otp_not_matched'));
            }
        }
        return $this->sendError('Not Found.', trans('messages.user_not_found'));
    }
    public function delete(Request $request) {
       $phone           = $request->phone;
       $user            = User::where('phone_number' , $phone)->first();
       if($user) {
          DB::table('phone_verifications')->where([
            'phone' =>  $user->phone_number,
          ])->delete();
          $user->delete();
          $success    = null;
          return $this->sendResponse($success, trans('messages.user_deleted_successfully'));
       }
    }

    public function user_info() {
        $user             = Auth::guard('api')->user();
        if($user) {
            $success      = null;
            return $this->sendResponse($user, 'User Info.');
        }
     }


    public function change_password(Request $request) {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    return $this->sendError('Password Wrong!.', trans('messages.check_your_old_password'));
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    return $this->sendError('Passwords Are Similar!.', trans('messages.please_enter_a_password_which_is_not_similar_then_current_password'));
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $success    = null;
                    return $this->sendResponse($success,  trans('messages.password_updated_successfully'));
                }
            } catch (\Exception $ex) {
                return $this->sendError('Server Error.', trans('messages.server_error'));
            }
       }
    }

    public function resendCode(Request $request) {

        //var_dump($response);
        DB::beginTransaction();
        try {
        $validator = Validator::make($request->all(), [
            'phone_number'        => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

          $user = User::where('phone_number', $request->phone_number)->first();
        if($user) {
            try {
                $otp                       = '1234';//rand(1000, 9999);
                DB::table('phone_verifications')->updateOrInsert(['phone' => $request['phone_number']],
                [
                'token'      => $otp,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
                $message = str_replace("#OTP#", $otp, 'Your otp is #OTP#.');
                $response = InfobipSms::send($request['phone_number'],  $message);
                $success    = null;
                DB::commit();
                return $this->sendResponse($success,  trans('messages.verfication_code_resended_successfully'));
            } catch (\Exception $e) {

                DB::rollback();
                return $this->sendError('verfication error.', trans('messages.faild_to_send_sms.'));
            }
        }
        }catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('server error.', trans('messages.something_wrong_happen'));
        }
    }

    public function update_profile(Request $request){

        DB::beginTransaction();
        try {
        $validator = Validator::make($request->all(), [
            'full_name'            => 'required',
            'user_name'            => 'required|unique:users,user_name,'.Auth::guard('api')->user()->id,
            'phone_number'         => ['required',Rule::unique('users','phone_number')->ignore(Auth::guard('api')->user()->id)->where(function ($query) use ($request) { return $query->where('isVerified', 1); })],
            'email'                => 'required|unique:users,email,'.Auth::guard('api')->user()->id,
            'photo'                => 'nullable|image|mimes:jpg,bmp,png'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $user = Auth::guard('api')->user();
        if(!empty($request->password)) {
            $user->password   = bcrypt($request->password);
        }
        if($user) {
            if($request->hasFile('photo')){
                $image = $request->file('photo');
                $image_ext = $image->getClientOriginalExtension();
                $path = rand(123456, 999999) . "." . $image_ext;
                $destination_path = public_path("storage/uploads/users/");
                $image->move($destination_path, $path);
                $image_name  = $path ;
            } else{
                $image_name = $user->getRawOriginal('photo');
            }

             $user->full_name             =    $request->full_name;
             $user->user_name             =    $request->user_name;
             if(!empty($request->phone_number) && $request->phone_number != $user->phone_number) {
                $otp                      = '1234';//rand(1000, 9999);
                DB::table('phone_verifications')->updateOrInsert(['phone' => $request['phone_number']],
                [
                'token'      => $otp,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
                DB::commit();
                $message = str_replace("#OTP#", $otp, 'Your otp is #OTP#.');
                $response = InfobipSms::send($request['phone_number'],  $message);
                $success                    = null; 
                return $this->sendResponse($success ,  trans('messages.verfication_code_sended_successfully.'));  
               /* $user->phone_number         =  $request->phone_number;
                $user->isVerified           =  0;  */
             } else {
                $user->phone_number            =  $request->phone_number;
                $user->email                   =     $request->email;
                $user->photo                   =     $image_name;
                $user->save();
                $user['token']                 =  $user->createToken('MyApp')-> accessToken;
                $success                       = null;
                DB::commit();
                return $this->sendResponse($user ,  trans('messages.data_successfully_updated'));
             }
            
        }
        }catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }

    }

    public function verfiyNumberAfterUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'phone_number'         => 'required',
            'otp'                  => 'required',
            'full_name'            => 'required',
            'user_name'            => 'required|unique:users,user_name,'.Auth::guard('api')->user()->id,
            'email'                => 'required|unique:users,email,'.Auth::guard('api')->user()->id,
            'photo'                => 'nullable|image|mimes:jpg,bmp,png'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $user =   Auth::guard('api')->user();
        if($user)
        {
           
            $data = DB::table('phone_verifications')->where([
                'phone' => $request['phone_number'],
                'token' => $request['otp'],
            ])->first();

            if($data)
            {
                DB::table('phone_verifications')->where([
                    'phone' => $request['phone_number'],
                    'token' => $request['otp'],
                ])->delete();

                if(!empty($request->password)) {
                    $user->password   = bcrypt($request->password);
                }
                if($request->hasFile('photo')){
                    $image = $request->file('photo');
                    $image_ext = $image->getClientOriginalExtension();
                    $path = rand(123456, 999999) . "." . $image_ext;
                    $destination_path = public_path("storage/uploads/users/");
                    $image->move($destination_path, $path);
                    $image_name  = $path ;
                } else{
                    $image_name = $user->getRawOriginal('photo');
                }
                $user->full_name               =    $request->full_name;
                $user->user_name               =    $request->user_name;
                $user->phone_number            =    $request->phone_number;
                $user->email                   =    $request->email;
                $user->photo                   =    $image_name;
                $user->save();
               // $user['token']    =  $user->createToken('MyApp')-> accessToken;

                    $response = [
                        'success' => true,
                        'data'    => null,
                        'message' => 'login',
                    ];
                    return response()->json($response, 302);
            }
            else{
                return $this->sendError('Not matched.', trans('messages.phone_number_and_otp_not_matched'));
            }
        }
        return $this->sendError('Not Found.', trans('messages.user_not_found'));

      

    }

    public function updatefcmToken(Request $request) {
         $user              = auth()->user();
         $user->fcm_token   = $request->fcm_token;
         $user->save();
         $success           = null;
         return $this->sendResponse($success  , 'fcm token successfully updated.');
    }
    function generateRandomString($length = 10) {
        $characters         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength   = strlen($characters);
        $randomString       = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;

    }

    public function allNotifications() {
        Notification::where('user_id' ,  Auth::guard('api')->user()->id)->where('is_read' , 0)->update(['is_read' => 1]);
        return  NotificationResource::collection(Notification::with(['mazad:id,product_name' , 'mazad.images' , 'product:id,product_name' ,  'product.images'])->where('user_id' ,  Auth::guard('api')->user()->id)->get());
    }

    public function unreadNotifications() {
        
        $count = Notification::where(['user_id' => Auth::guard('api')->user()->id , 'is_read' => 0])->count();
        $success   = true;
        return $this->sendResponse($count , 'data returned successfully');
     }

    public function generateInviteCode() {
        return substr(md5(rand(0, 9) . time()), 0, 32); 
    }

    // rate user 
    public function addToRates(Request $request) {

        $validator = Validator::make($request->all(), [
            'rate'              => 'required',
            'owner_id'          => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $rate = Rate::where('owner_id',$request->owner_id)->where('user_id',Auth::guard('api')->user()->id)->first();
        if( $rate ) {
            $rate->rate          = $request->rate;
            $rate->comment       = $request->comment;
            $rate->save();   
        }else {
            $data = new Rate();
            $data->owner_id      = $request->owner_id;
            $data->user_id       = Auth::guard('api')->user()->id;
            $data->rate          = $request->rate;
            $data->comment       = $request->comment;
            $data->save();    
        } 
        
        $success    = null;
        return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
    }


}
