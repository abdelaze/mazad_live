<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
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
            'detail'             => $this->getTranslation('detail', App::getLocale()),
        ];
    }
}
