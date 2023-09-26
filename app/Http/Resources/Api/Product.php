<?php

namespace App\Http\Resources\Api;

use Auth;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
          
            $date1 = new DateTime(\Carbon\Carbon::now()->addHours(2)->format('Y-m-d H:i'));
            $date2 = $date1->diff(new DateTime($this->end_time));
            $days    =  $date2->days;
            $hours   =  $date2->h;
            $minutes =  $date2->i;
            $seconds =  $date2->s;
            
            return [
                'id'                         => $this->id,
                'product_name'               => $this->product_name,
                'product_desc'               => $this->product_desc,
                'end_time'                   => date('H:i', strtotime($this->end_time)),
                'end_time'                   => date('Y-m-d', strtotime($this->end_time)),
                'remain_days'                => $days,
                'remain_hours'               => $hours,
                'remain_minutes'             => $minutes,
                'remain_seconds'             => $seconds,
                'price'                      => $this->price,
                'currency'                   => $this->currency,
                'views'                      => $this->views,
                'category'                   => $this->whenLoaded('category'),
                'subcategory'                => $this->whenLoaded('subcategory'),
                'brand'                      => $this->whenLoaded('brand'),
                'country'                    => $this->whenLoaded('country'),
                'state'                      => $this->whenLoaded('state'),
                'city'                       => $this->whenLoaded('city'),
                'user'                       => $this->whenLoaded('user'),
                'images'                     => $this->whenLoaded('images'),
                'options'                    => $this->whenLoaded('options'),
                'options_ar'                 => $this->whenLoaded('options_ar'),
                'favorite'                   => (isset(Auth::guard('api')->user()->id)) ?  ($this->favorite->count() > 0 ? 1 : 0) : 0 ,
            ];
    }
}
