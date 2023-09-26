<?php

namespace App\Http\Requests\Api\Mazdat;

use Carbon\Carbon;
use App\Traits\ResponseJson;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

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
            'category_id'                    => ['required', Rule::exists('categories', 'id')],
            'subcategory_id'                 => ['required', Rule::exists('sub_categories', 'id')],
          //  'country_id'                     => ['required', Rule::exists('countries', 'id')],
         //   'state_id'                       => ['required', Rule::exists('states', 'id')],
         //   'city_id'                        => ['sometimes',Rule::exists('cities', 'id')],
            'product_name'                   => ['required','min:3','max:300'],
          //  "start_price"                    => "required|regex:/^\d{1,13}(\.\d{1,4})?$/",
            'product_desc'                   => ['required','min:3','max:500'],
            'type'                           => 'required',Rule::in(['1', '2']),
            'display_date'                   => 'required_if:type,2|date|date_format:Y-m-d H:i',
            'end_date'                       => 'required|date|date_format:Y-m-d',
            "min_price"                      => "sometimes|regex:/^\d{1,13}(\.\d{1,4})?$/",
          //  'currency'                       => ['required'],
            'image'                          => 'sometimes|array|min:1|max:6',
            'image.*'                        => 'image|mimes:jpeg,png,jpg,gif,svg',
            'video'                          => 'sometimes|array|min:1|max:6',
            'video.*'                        => 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400'
        ];
    }

    //  unique:grades,name->ar
    //  unique:grades,name->en
    
    public function messages()
    {
        return [
            'category_id.required'          => trans('validation.required'),
            'subcategory_id.required'       => trans('validation.required'),
            'category_id.exists'            => trans('validation.exists'),
            'subcategory_id.exists'         => trans('validation.exists'),
            'country_id.required'           => trans('validation.required'),
            'state_id.required'             => trans('validation.required'),
            'city_id.required'              => trans('validation.required'),
            'country_id.exists'             => trans('validation.exists'),
            'state_id.exists'               => trans('validation.exists'),
            'city_id.exists'                => trans('validation.exists'),
            'product_name.required'         => trans('validation.required'),
            'product_name.max'              => trans('validation.max'),
            'product_name.min'              => trans('validation.min'),
            'product_desc.required'         => trans('validation.required'),
            'product_desc.max'              => trans('validation.max'),
            'product_desc.min'              => trans('validation.min'),
            'image.required'                => trans('validation.required'),
            'type.required'                 => trans('validation.required'),
            'type.in'                       => trans('validation.in'),
            'display_date.required_if'         => trans('messages.display_date'),
            'display_date.date_format'      => trans('validation.date_format'),
            'end_date.required'      => trans('messages.end_date'),
            'end_date.date_format'      => trans('validation.date_format'),
          //  'start_price.required'          => trans('validation.required'),
           // 'start_price.regex'             => trans('validation.regex'),
            'min_price.required'            => trans('validation.required'),
            'min_price.regex'               => trans('validation.regex'),
            'currency.required'             => trans('validation.required'),
           
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException( $validator , $this->sendError('Validation Error.', $validator->errors()->first()));
    }
}
