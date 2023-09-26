<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\ResponseJson;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    use ResponseJson;

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
            'name'                           => ['required','min:3','max:255',Rule::unique('brands','name->en')->where('category_id' , $this->category_id)->whereNull('deleted_at')],
            'name_ar'                        => ['required','min:3','max:255',Rule::unique('brands','name->ar')->where('category_id' , $this->category_id)->whereNull('deleted_at')],
            'category_id'                    => ['required','exists:categories,id'],
            'sub_category_id'                => ['required','exists:sub_categories,id'],
            'image'                          => 'image|mimes:jpg,png,jpeg,gif,svg',
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
            'image.required'            => trans('validation.required'),
        ];
    }

  
}
