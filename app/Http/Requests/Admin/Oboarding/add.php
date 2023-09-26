<?php

namespace App\Http\Requests\Admin\Oboarding;

use Illuminate\Foundation\Http\FormRequest;

class add extends FormRequest
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
    public function rules()
    {
        return [
            'title'                          => 'required|max:500',
            'title_ar'                       => 'required|max:500',
            'content'                        => 'required',
            'content_ar'                     => 'required',
            'image'                          => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ];
    }

    //  unique:grades,name->ar
    //  unique:grades,name->en
    
    public function messages()
    {
        return [
            'title.required'       => trans('validation.required'),
            'title_ar.required'    => trans('validation.required'),
            'content.required'     => trans('validation.required'),
            'image.required'       => trans('validation.required'),
        ];
    }
}
