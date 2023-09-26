<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'id'               => $this->id,
            'name'             => $this->getTranslation('name', App::getLocale()),
            'image'            => $this->image,
            'subcategories'    => $this->whenLoaded('subcategories'),
            'brands'           => $this->whenLoaded('brands'),
            'attributes'       => $this->whenLoaded('attributes'),
        ];
    }
}
