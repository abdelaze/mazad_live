<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class Option extends JsonResource
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
            "id"                  =>   $this->id,
            "category_id"         =>   $this->category_id,
            "input_label"         =>   $this->getTranslation('input_label', App::getLocale()),
            "input_type"          =>   $this->input_type,
            "input_name"          =>   $this->input_name,
            "options"             =>   json_decode($this->options),
            "options_ar"          =>   json_decode($this->options_ar),
            "options_label"       =>   json_decode($this->options_label),
            "options_label_ar"    =>   json_decode($this->options_label_ar),
       ];
    }
}
