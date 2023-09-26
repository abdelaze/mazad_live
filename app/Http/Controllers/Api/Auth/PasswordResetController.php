<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use Pnlinh\InfobipSms\Facades\InfobipSms;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController as BaseController;
class PasswordResetController extends BaseController
{
    public function reset_password_request(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());   
        }

        $customer = User::Where(['phone_number' => $request['phone']])->first();

        if (isset($customer)) {
            $token = '1234';//rand(1000,9999);
            DB::table('password_resets')->insert([
                'email' => $customer['phone_number'],
                'token' => $token,
                'created_at' => now(),
            ]);
            $response = InfobipSms::send($request['phone'],$token);
            $success    = null;    
            return $this->sendResponse($success,  trans('messages.otp_sent_successfully'));
            
        }
         return $this->sendError('not-found.', trans('messages.phone_number_not_found'));
       }catch (\Exception $e) { 
        return $this->sendError('Verfication Error.', trans('messages.faild_to_send_sms.'));
      } 
    }

    public function verify_token(Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'reset_token'=> 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());    
        }
        $user=User::where('phone_number', $request->phone)->first();
        if (!isset($user)) {
            return $this->sendError('not-found.', trans('messages.phone_number_not_found'));
        }

        $data = DB::table('password_resets')->where(['token' => $request['reset_token'],'email'=>$user->phone_number])->first();
        if (isset($data)) {
            $success    = null;    
            return $this->sendResponse($success, trans('messages.OTP_found_you_can_proceed'));

        }
        return $this->sendError('Invalid.', trans('messages.invalid_otp'));
    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reset_token'=> 'required',
            'password'=> 'required|min:6',
            'confirm_password'=> 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $data = DB::table('password_resets')->where(['token' => $request['reset_token']])->first();
        if (isset($data)) {
            if ($request['password'] == $request['confirm_password']) {
                DB::table('users')->where(['phone_number' => $data->email])->update([
                    'password' => bcrypt($request['confirm_password'])
                ]);
                DB::table('password_resets')->where(['token' => $request['reset_token']])->delete();
                $success    = null;  
                return $this->sendResponse($success, trans('messages.password_changed_successfully'));
            }
            return $this->sendError('Not Match.',  trans('messages.password_did_not_match'));
        }
        return $this->sendError('Invalid.',  trans('messages.invalid_otp'));
    }
}
