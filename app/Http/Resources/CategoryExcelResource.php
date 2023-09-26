<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryExcelResource extends JsonResource
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
            'name_en'             => $this->getTranslation('name', 'en'),
            'name_ar'             => $this->getTranslation('name', 'ar'),
            'image'               => $this->getRawOriginal('image'),
            //'status'              => $this->status,
        ];
    }
}
