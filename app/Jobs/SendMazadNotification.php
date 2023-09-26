<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendMazadNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $mazad_id;
    public $title;
    public $title_ar; 
    public $details;
    public $details_ar;

    public function __construct($data , $mazad_id , $title  , $title_ar, $details , $details_ar)
    {
        $this->data         = $data;
        $this->mazad_id     = $mazad_id;
        $this->title        = $title;
        $this->details      = $details;
        $this->title_ar     = $title_ar;
        $this->details_ar   = $details_ar;

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
                            'title'               =>   $this->title,
                            'body'                =>   $this->details,
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
                    $notifcation->title      =  ['en' => $this->title    , 'ar' =>$this->title_ar];
                    $notifcation->details    =  ['en' => $this->details  , 'ar' => $this->details_ar];
                    $notifcation->mazad_id   =   $this->mazad_id ;
                    $notifcation->user_id    =   $user->id;
                    $notifcation->type       =  "mazad";
                    $notifcation->save();

               }
        }
    }
}
