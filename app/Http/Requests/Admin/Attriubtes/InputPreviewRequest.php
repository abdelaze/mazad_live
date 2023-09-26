<?php

namespace App\Http\Requests\Admin\Attriubtes;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InputPreviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'input_label'       => 'required|string|min:3|max:50',
            'input_label_ar'    => 'required|string|min:3|max:50',
            'input_type'        => ['required', Rule::in(['number','color', 'radio', 'checkbox', 'text', 'textarea','select'])],
            'input_name'        => 'required|string|min:3|max:50',
            'select-options'    => ['required_if:input_type,select','array','min:2','max:20'],
            'radio-options'     => ['required_if:input_type,radio','array','min:2','max:20'],
            'checkbox-options'  => ['required_if:input_type,checkbox','array','min:2','max:20']
        ];
    }
}
