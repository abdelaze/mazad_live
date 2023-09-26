<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Rate extends JsonResource
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
            'user'                  => $this->whenLoaded('user'),
            'mazad'                 => $this->whenLoaded('mazad'),
            'rate'                  => $this->rate,
            'comment'               => $this->comment,
        ];
    }
}
