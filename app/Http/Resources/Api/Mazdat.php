<?php

namespace App\Http\Resources\Api;

use DateTime;
use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class Mazdat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->type == 2 ) {
             /*$datestr= $this->display_date;
             $date=strtotime($datestr);
             $diff=$date-time();
            // dd(time());
             $days=floor($diff/(60*60*24));
             $hours=round(($diff-$days*60*60*24)/(60*60)); */

             $date = new DateTime($this->display_date);

             $date1 = new DateTime(\Carbon\Carbon::now()->addHours(2)->format('Y-m-d H:i'));
             if( $date1 <  $date) {
                $date2   =  $date1->diff($date);
                $days    =  $date2->days;
                $hours   =  $date2->h;
                $minutes =  $date2->i;
                $seconds =  $date2->s;
             }else {
                $days      = 0;
                $hours     = 0; 
                $minutes   = 0;
                $seconds   = 0;
             }
          
           // echo $date2->days.'Total days'."\n";
           // echo $date2->y.' years'."\n";
           // echo $date2->m.' months'."\n";
             

              if($days == 0 && $hours == 0 && $minutes == 0 && $seconds == 0) {
                  $open = 1 ;
              }else {
                  $open = 0 ; 
              }
            
        }else {
            $days      = 0;
            $hours     = 0; 
            $minutes   = 0;
            $seconds   = 0;
            $open = 1 ;
        }
        return [
            'id'                         => $this->id,
            'product_name'               => $this->product_name,
            'product_desc'               => $this->product_desc,
            'display_time'               => date('H:i', strtotime($this->display_date)),
            'display_date'               => date('Y-m-d', strtotime($this->display_date)),
            'remain_days'                => $days,
            'remain_hours'               => $hours,
            'remain_minutes'             => $minutes,
            'remain_seconds'             => $seconds,
           // 'start_price'                => $this->start_price,
            'min_price'                  => $this->min_price,
            'end_date'                   => $this->end_date,
          //  'currency'                   => $this->currency,
            'views'                      => $this->views,
            'category'                   => $this->whenLoaded('category'),
            'subcategory'                => $this->whenLoaded('subcategory'),
           // 'country'                    => $this->whenLoaded('country'),
           // 'state'                      => $this->whenLoaded('state'),
           // 'city'                       => $this->whenLoaded('city'),
            'type'                       => $this->type, 
            'user'                       => $this->whenLoaded('user'),
            'images'                     => $this->whenLoaded('images'),
            'videos'                     => $this->whenLoaded('videos'),
            'favorite'                   => (isset(Auth::guard('api')->user()->id)) ?  ($this->favorite->count() > 0 ? 1 : 0) : 0 ,
            'is_open'                    =>  (bool)$open,
        ];
    }
}
