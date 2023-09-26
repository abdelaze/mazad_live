<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StroeUserRequest extends FormRequest
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
            'first_name'                 => 'required',
            'last_name'                  => 'required',
            'gender'                     => 'required',
            'phone_number'               => 'required|unique:users',
            'password'                   => 'required|min:6',
            'confirm-password'           => 'required|same:password',
            'category'                   => 'required',
            'salon_id'                   => 'required',
            
        ];
    }
}
