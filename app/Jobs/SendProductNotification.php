<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendProductNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $product_id;
    public $title;
    public $title_ar; 
    public $details;
    public $details_ar;

    public function __construct($data , $product_id , $title  , $title_ar, $details , $details_ar)
    {
        $this->data         = $data;
        $this->product_id   = $product_id;
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
        $SERVER_API_KEY              = '';
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
                            'mazad_id'            =>    $this->product_id,
                            'type'                =>   'product'
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
                    $notifcation->product_id =   $this->product_id ;
                    $notifcation->user_id    =   $user->id;
                    $notifcation->type       =  "product";
                    $notifcation->save();

               }
        }
    }
}
