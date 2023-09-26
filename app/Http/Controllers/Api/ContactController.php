<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use Validator;
use DB;

use Mail;
use App\Mail\ContactMail;

class ContactController extends BaseController
 {

    public function store( Request $request )
 {
        DB::beginTransaction();
        try {
            $validator = Validator::make( $request->all(), [
                'name'          => 'required',
                'email'         => 'required|email',
                'message'       => 'required'
            ] , [
                'name.required' => trans('messages.name.required'),
                'email.required' => trans('messages.email.required'),
                'message.required' => trans('messages.message.required'),
            ]);
            if ( $validator->fails() ) {
                return $this->sendError( 'Validation Error.', $validator->errors()->first() );
            }
            $contact            = new ContactUs();
            $contact->name      = $request->name ;
            $contact->email     = $request->email ;
            $contact->message   = $request->message ;
            $contact->save();
            $adminEmail = 'noreply@mzadlive.com';
            Mail::to( $adminEmail )->send( new ContactMail( $contact ) );
            DB::commit();
            $success           = null;

            return $this->sendResponse( $success, trans('translation.Thank you for contact us. we will contact you shortly.'));
        } catch ( \Exception $e ) {
            dd($e);
            DB::rollback();
            return $this->sendError( 'Server Error.',  trans('messages.something_wrong_happen'));
        }

    }
}
