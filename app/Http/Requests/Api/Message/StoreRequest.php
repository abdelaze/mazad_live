<?php

namespace App\Http\Requests\Api\Message;

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
           // 'from'                           => ['required', Rule::exists('users', 'id')],
            'to'                             => ['required', Rule::exists('users', 'id')],
            'image'                          => 'sometimes,image|mimes:jpeg,png,jpg,gif,svg',
            'message'                        => ['sometimes'],
            'voice'                          => ['sometimes'],
            'video'                          => ['sometimes'],
          
        ];
    }

    //  unique:grades,name->ar
    //  unique:grades,name->en
    
    public function messages()
    {
        return [
            'to.required'                   => trans('validation.required'),
            'form.required'                 => trans('validation.required'),
            'to.exists'                     => trans('validation.exists'),
            'from.exists'                   => trans('validation.exists'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException( $validator , $this->sendError('Validation Error.', $validator->errors()->first()));
    }
}
