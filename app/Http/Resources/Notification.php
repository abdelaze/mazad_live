<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
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
           
            'title'                    => $this->title,
            'details'                  => $this->details,
            'mazad'                    => $this->whenLoaded('mazad'),
            'product'                  => $this->whenLoaded('product'),
          
        ];
    }
}