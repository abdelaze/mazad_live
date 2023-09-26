<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class Onboarding extends JsonResource
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
            'title'        => $this->getTranslation('title', App::getLocale()),
            'content'      => $this->getTranslation('content', App::getLocale()),
            'image'        => $this->image
        ];
    }
}
