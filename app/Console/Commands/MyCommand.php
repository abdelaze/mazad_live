<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Mazdat;
use Illuminate\Console\Command;
use App\Jobs\SendAlertNotification;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Alert Notification before mazad start';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
                              
        $mazdats                 =  Mazdat::whereDate('display_date', '=',  date( 'Y-m-d', strtotime(Carbon::create(Carbon::now()->addHours(2))->toDateString())))
                                           ->whereTime('display_date', '>=', date('H:i' , strtotime(Carbon::create(Carbon::now()->addHours(2)->addMinutes(5))->toTimeString())))
                                           ->where(['status' => 1 , 'is_closed' => 0 , 'is_notify'  => 0 ])
                                           ->get();

        foreach($mazdats as $mazad) {
            $mazad_id       = $mazad->id; 
            $product_name   = $mazad->product_name;
            User::select('id' , 'fcm_token')
                            ->chunk(50,function($data)  use( $mazad_id , $product_name){
                                dispatch(new SendAlertNotification($data ,$product_name ));
                            });
        }
                                        
    }
}
