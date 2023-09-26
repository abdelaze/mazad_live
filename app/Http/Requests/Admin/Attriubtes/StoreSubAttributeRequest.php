<?php

namespace App\Http\Requests\Admin\Attriubtes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubAttributeRequest extends FormRequest
{

    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {

        return [
            'input_label'       => ['required','string','min:3','max:50',Rule::unique('attributes','input_label->en')->whereNull('deleted_at'),Rule::unique('sub_attributes','input_label->en')->whereNull('deleted_at')],
            'input_label_ar'    => ['required','string','min:3','max:50',Rule::unique('attributes','input_label->ar')->whereNull('deleted_at'),Rule::unique('sub_attributes','input_label->ar')->whereNull('deleted_at')],
            'input_type'        => ['required',Rule::in(['number','color','radio','checkbox','text','textarea','select'])],
            'input_name'        => ['required','string','min:2','max:50',Rule::unique('attributes','input_name'),Rule::unique('sub_attributes','input_name')],
            'sub_category_id'   => 'required|exists:sub_categories,id',
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
        return $payload;
    }

}
