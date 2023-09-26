<?php

namespace App\Http\Requests\Admin\Attriubtes;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'input_label'       => ['required','string','min:3','max:50',Rule::unique('attributes','input_label->en')->whereNull('deleted_at'),Rule::unique('sub_attributes','input_label->en')->whereNull('deleted_at')],
            'input_label_ar'    => ['required','string','min:3','max:50',Rule::unique('attributes','input_label->ar')->whereNull('deleted_at'),Rule::unique('sub_attributes','input_label->ar')->whereNull('deleted_at')],
            'input_type'        => ['required',Rule::in(['number','color','radio','checkbox','text','textarea','select'])],
            'input_name'        => ['required','string','min:2','max:50',Rule::unique('attributes','input_name'),Rule::unique('sub_attributes','input_name')],
            'category_id'       => 'required|exists:categories,id',
            'options'           => ['required_if:input_type,radio,checkbox,select','array','min:2','max:20'],
            'options_ar'        => ['required_if:input_type,radio,checkbox,select','array','min:2','max:20'],
            'options_label'     => ['required_if:input_type,radio,checkbox,select','array','min:2','max:20'],
            'options_label_ar'  => ['required_if:input_type,radio,checkbox,select','array','min:2','max:20']
        ];
    }

    public function validated($key = null, $default = null):array
   {
       $payload = parent::validated();
       if (is_null(request('options')) || is_null(request('options_label'))) {
           unset($payload['options']);
           unset($payload['options_label']);
       }elseif(!is_null(request('options')) && !is_null(request('options_label'))){
           $payload['options'] = request('options');
           $payload['options_label'] = request('options_label');
       }

       if (is_null(request('options_ar')) || is_null(request('options_label_ar'))) {
        unset($payload['options_ar']);
        unset($payload['options_label_ar']);
    }elseif(!is_null(request('options_ar')) && !is_null(request('options_label_ar'))){
        $payload['options_ar'] = request('options_ar');
        $payload['options_label_ar'] = request('options_label_ar');
    }
       return $payload;
   }
}
