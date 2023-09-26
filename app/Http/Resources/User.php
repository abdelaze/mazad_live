<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                          => $this->id,
            'first_name'                  => $this->first_name, 
            'last_name'                   => $this->last_name,
            'photo'                       => !empty($this->photo)  ?  $this->photo : 'null',
            'phone_number'                => $this->phone_number,
            'working_hours'               => $this->whenLoaded('working_hours'),
            'salones'                     => $this->whenLoaded('salones'),
            'rates'                       => $this->whenLoaded('rates'),
            'category'                    => $this->whenLoaded('category'),
            'reservations'                => $this->whenLoaded('reservations'),
            'rate'                        => (!empty($this->rates)) ? round($this->whenLoaded('rates')->avg('rate') , 2) : 0 ,
            'rate_count'                  => (!empty($this->rates)) ? $this->whenLoaded('rates')->count('rate') : 0 ,
            'type'                        => $this->type,
            'invite_code'                 => $this->invite_code,
        ];
    }
}
