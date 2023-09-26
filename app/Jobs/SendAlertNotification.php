<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendAlertNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;
    public $title;
    public $mazad_id;

    public function __construct($data , $title , $mazad_id)
    {
        $this->data         = $data;
        $this->title        = $title;
        $this->mazad_id     = $mazad_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $SERVER_API_KEY              = 'AAAA6VrAzvE:APA91bELB98C-KCuB4tGXQ2neDGhB7PhHzjqMW_woj8LR4kk26XJbIRWcAaGNrkqXSDr11ZkykXyaA7DmGHJpGyNWlpDo0PpeFjoEg1ggMMLmhTnUA8g3T1QscQcoyyeDtasXtmXZzW1';
        foreach($this->data as $user ) {
        
            if (!empty($user->fcm_token)) {
                  
                    $data = [
                        'registration_ids'        =>   [ $user->fcm_token ],
                        'notification'            =>   [
                            'title'               =>   $this->title . 'mazad will start after five minutes',
                            'body'                =>   'after five minute you can see the live of this mazad',
                            'sound'               =>   'alert',
                            'content_available'   =>   true,
                            'priority'            =>   'high',
                        ],
                        'data'                    =>   [
                            'click_action'        =>   'FLUTTER_NOTIFICATION_CLICK',
                            'mazad_id'            =>    $this->mazad_id,
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

                    $notifcation             = new Notification();
                    $notifcation->title      =  ['en' => $this->title . 'mazad will start after five minutes'    , 'ar' => $this->title . 'mazad will start after five minutes'];
                    $notifcation->details    =  ['en' => 'after five minute you can see the live of this mazad'  , 'ar' => 'after five minute you can see the live of this mazad'];
                    $notifcation->mazad_id   =   $this->mazad_id ;
                    $notifcation->user_id    =   $user->id;
                    $notifcation->type       =  "mazad";
                    $notifcation->save();

               }
        }
    }
}
