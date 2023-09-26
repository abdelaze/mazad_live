<?php

namespace App\Http\Controllers\Api;

use File;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Message\StoreRequest;
use DB;
use Illuminate\Validation\Rule;

class MessageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $validated                         =  $request->validated();
            $validated['from']                 =  Auth::guard('api')->user()->id;
            if($request->file('image')) {
                $file     = $request->file('image');
                $path     = public_path('uploads/messages/images');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileName                   = uniqid() . '_' . trim($file->getClientOriginalName());
                $file->move($path, $fileName);
                $validated['image']         =  $fileName;
                
            }

            if($request->file('voice')) {
                $file     = $request->file('voice');
                $path     = public_path('uploads/messages/voices');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileName                   = uniqid() . '_' . trim($file->getClientOriginalName());
                $file->move($path, $fileName);
                $validated['voice']          =  $fileName;
                
            }

            if($request->file('video')) {
                $file     = $request->file('video');
                $path     = public_path('uploads/messages/videos');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileName                   = uniqid() . '_' . trim($file->getClientOriginalName());
                $file->move($path, $fileName);
                $validated['video']         =  $fileName;
            }

            $ref            = '';
            $from           = Auth::guard('api')->user()->id;
            $to             = $validated['to']; 
            $chat = UserChat::where('member1',$from)->where('member2',$to)->Orwhere('member1',$to)->where('member2',$from)->first();
           if(empty($chat)) {
    
                  $cht          = new  UserChat();
                  $cht->chat_id = $from.$to ;
                  $cht->member1 = $from ;
                  $cht->member2 = $to ;
                  $cht->save();
                  $ref = $cht->chat_id ;
    
           }else {
                  $ref = $chat->chat_id ;
           }
           $validated['user_chat_id']         =  $ref;
           $message                           =  Message::create($validated);
        
          /* $postData = [

            'id'            => $message->id,
            'message'       => !empty($request->message)  ? $request->message  : 'none',
            'video'         => !empty($request->video)    ? $request->video    : 'none',
            'image'         => !empty($request->image)    ? $request->image    : 'none',
            'voice'         => !empty($request->vocie)    ? $request->voice  : 'none',
            'from'          => $from,
            'to'            => $to,
            'is_read'       => 0,
            'send_date'     => Carbon::now()->addHour(2)->format('d-m-y H:i')  ,
            'send_time'     => Carbon::now()->addHour(2)->format('H:i'),
            ];

            $factory        = (new Factory)->withServiceAccount(__DIR__.'/firebaseKey.json');
            $firestore      =  $factory->createFirestore();
            $database       =  $firestore->database();
        //    $testRef        =  $database->collection($ref)->document($message->id)->set($postData);
            $testRef        =  $database->collection('chat')->document($ref)->add($postData);
      */
            DB::commit();
            $success   = true; 
            return $this->sendResponse( $ref , trans('translation.data_returned_successfully'));
            
        }catch(Exception $e) {
           DB::rollback();
           return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy(Message $message)
    {
        $message->delete();
        $factory        = (new Factory)->withServiceAccount(__DIR__.'/firebaseKey.json');
        $firestore      =  $factory->createFirestore();
        $database       =  $firestore->database();
        $testRef        =  $database->collection($message->user_chat_id)->document($message->id)->delete();
        $success   = true; 
        return $this->sendResponse( $success , trans('translation.data_deleted_successfully'));
    }

    public function getUserChats() {
       /* $chats  = UserChat::where('member1' , Auth::guard('api')->user()->id)
                            ->orWhere('member2' , Auth::guard('api')->user()->id)
                            ->with(['member1' , 'member2'])->select('id' , 'member1' , 'member2' , 'chat_id' , 'last_message')
                            //->orderBy('last_message.id' , 'DESC')
                            ->join('messages', 'messages.user_chat_id', '=', 'user_chats.chat_id')
                            ->select('messages.*' , 'user_chats.*')
                            ->orderBy('messages.id' , 'DESC')
                            ->get();*/
          $chats   = Message::with('user_chat.member1')->with('user_chat.member2')->orderBY('id' , 'DESC')->get()->unique('user_chat_id');   
         return $this->sendResponse( array_values(collect($chats)->toArray()) , trans('translation.data_returned_successfully'));
    }

    public function getChatId(Request $request) {
        $validator = Validator::make($request->all(), [
            'to'                             => ['required', Rule::exists('users', 'id')],
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        $ref            = '';
        $from           = Auth::guard('api')->user()->id;
        $to             = $request->to; 
        $chat = UserChat::where('member1',$from)->where('member2',$to)->Orwhere('member1',$to)->where('member2',$from)->first();
       if(empty($chat)) {

              $cht          = new  UserChat();
              $cht->chat_id = $from.$to ;
              $cht->member1 = $from ;
              $cht->member2 = $to ;
              $cht->save();
              $ref = $cht->chat_id ;

       }else {
              $ref = $chat->chat_id ;
       }
        
       return $this->sendResponse( $ref , trans('translation.data_returned_successfully'));
 
    }

    public function testNotitcation() {
        $SERVER_API_KEY              = 'AAAA6VrAzvE:APA91bELB98C-KCuB4tGXQ2neDGhB7PhHzjqMW_woj8LR4kk26XJbIRWcAaGNrkqXSDr11ZkykXyaA7DmGHJpGyNWlpDo0PpeFjoEg1ggMMLmhTnUA8g3T1QscQcoyyeDtasXtmXZzW1';
        
        $data = [
            'registration_ids'        =>   [ auth()->guard('api')->user()->fcm_token ],
            'notification'            =>   [
                'title'               => "Test title",
                'body'                =>  "Test body",
                'sound'               =>   'alert',
                'content_available'   =>   true,
                'priority'            =>   'high',
            ],
            'data'                    =>   [
                'click_action'        =>   'FLUTTER_NOTIFICATION_CLICK',
                'type'                =>   'mazad'
            ],
            //  'to' =>  '/topics/topic',
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

        dd( $response);
    }
}
