<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use DB;
use App\Models\Notification;

class AdminOfferController extends BaseController
{
    public function addOffer(Request $request)
    {
        try {
        DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id'],
            'title'    => ['required', 'string', 'min:2', 'max:50'],
            'details'  => ['required', 'string', 'min:4', 'max:500'],
          //  'category' => ['required','array'],
            'end_date' => ['required','date','date_format:Y-m-d', 'after:yesterday'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $offer = Offer::where('salon_id' , $request->salon_id)->where('end_date' ,'>=' , date('Y-m-d' , strtotime(Carbon::now())))->first();
        if(!empty($offer)){
            return $this->sendResponse(true, 'you can not add offer until the exist offer ended');
        }
        $formInput = $request->all();
        if($formInput['end_date']  > date('Y-m-d' ,strtotime(Carbon::now()->addDays(14)))) {
            return $this->sendResponse(true, 'offer duration can not be more than 14 day');
        }
        $formInput['start_date'] = date('Y-m-d');
        //$formInput['category']   = json_encode($request->category, true);
        $offer  = Offer::create($formInput);
        $users  = User::whereHas('favorites', function($q) use ($request){
            $q->where('salon_id', $request->salon_id);
        })->orWhereHas('reservations', function($query) use ($request) {
            $query->where('salon_id', $request->salon_id);
        })->select('id' , 'fcm_token')->get();

        $SERVER_API_KEY              = 'AAAApY7z2Ao:APA91bEJUvwDz4O9e9-J397A3WkiPDHxebB5tzaGkQDvk6rq3eDhCQvRo9sYnaCh6mFd4Uvo-Qez6anW_LZJzz1yr3NPxaBpyxkgwM3X-JWtaI2c-YhfWIpQq5mR3FL7l4ly7vu3PAaf';
        foreach($users as $user) {
            if(!empty($user->fcm_token)) {

            $data = [
               "registration_ids"        =>   [$user->fcm_token],
               "notification"            =>   [
                   "title"               =>     $formInput['title'],
                   "body"                =>     $formInput['details'],
                   "sound"               =>     "alert",
                   "content_available"   =>     true,
                   "priority"            =>     "high",
               ],
               "data"                    =>   [
                                               "click_action"     => "FLUTTER_NOTIFICATION_CLICK",
                                               "type"             => "Offer"
                                             ],
               //  "to" =>  "/topics/topic",
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
               $notifcation             = new Notification();
               $notifcation->title      =  $request->title;
               $notifcation->details    =  $request->details;
               $notifcation->offer_id   =  $offer->id;
               $notifcation->salon_id   =  $request->salon_id;
               $notifcation->user_id    =  $user->id;
               $notifcation->save();
            }
        }

        DB::commit();
        return $this->sendResponse(true, "offer added successfully");
        }catch(Exception $e) {
            dd($e);
            DB::rollback();
            return $this->sendError('Not Found.', 'something wrong happen');
        }
    }

    public function getOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => ['required', 'exists:salons,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $offer = Offer::select('id', 'salon_id', 'title', 'details', 'start_date', 'end_date')->where(['salon_id' => $request->salon_id, ['end_date', '>=', date('Y-m-d')]])->first();
        if(!empty($offer)){
            return $this->sendResponse(true, $offer);
        }
        return $this->sendResponse(true, 'there are no offers');
    }

    public function updateOffer(Request $request)
    {
        $validator     = Validator::make($request->all(), [
            'offer_id' => ['required', 'exists:offers,id'],
            'title'    => ['required', 'string', 'min:2', 'max:50'],
            'details'  => ['required', 'string', 'min:4', 'max:500'],
           // 'category' => ['required','array'],
            'end_date' => ['required','date','date_format:Y-m-d', 'after:yesterday'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $offer = Offer::firstWhere(['id' => $request->offer_id, ['end_date', '>=', date('Y-m-d')]]);
        if($offer) {
           // $request->category   = json_encode($request->category, true);
            $offer->update($request->all());
            $offer->notifications()->update([
                 'title'     => $request->title,
                 'details'   => $request->details
            ]);
            return $this->sendResponse(true, "offer updated successfully");
        }else{
            return $this->sendError('Validation Error.', 'offer dosn\'t exist');
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => ['required', 'exists:offers,id']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $offer = Offer::find($request->offer_id);
        if ($offer){
            $offer->delete();
            return $this->sendResponse(true, 'offer deleted successfully.');
        }
        return $this->sendResponse(true, 'invalid offer.');

    }
}
