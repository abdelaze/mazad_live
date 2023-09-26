<?php

namespace App\Http\Requests\Admin\SubCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'name'                           => ['required','min:3','max:255',Rule::unique('sub_categories','name->en')->where('category_id' , $this->category_id)->whereNull('deleted_at')],
            'name_ar'                        => ['required','min:3','max:255',Rule::unique('sub_categories','name->ar')->where('category_id' , $this->category_id)->whereNull('deleted_at')],
            'category_id'                   => ['required','exists:categories,id'],
        ];
    }

    //  unique:grades,name->ar
    //  unique:grades,name->en
    
    public function messages()
    {
        return [
            'name.required'             => trans('validation.required'),
            'name_ar.required'          => trans('validation.required'),
            'name.min'                  => trans('validation.min'),
            'name_ar.min'               => trans('validation.min'),
            'name.max'                  => trans('validation.max'),
            'name_ar.max'               => trans('validation.max'),
            'name.unique'               => trans('validation.unique'),
            'name_ar.unique'            => trans('validation.unique'),
            'category_id.required'      => trans('validation.required'),
        ];
    }
}
