<?php

namespace App\Http\Requests\Tenant\Setup\CustomTypes;

use Illuminate\Foundation\Http\FormRequest;

class CustomTypesFormRequest extends FormRequest
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

    public function messages()
    {
        return [
            'description.required' => __("The field description is required."),
            'controller.required' => __("The field controller is required."),
            'field_name.required' => __("The field name is required."),
            'description.min' => __("The description must be at least 2 characters."),
            'controller.min' => __("The controller must be at least 2 characters."),
            'field_name.min' => __("The field name must be at least 2 character."),
           
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => ['required','min:2'],
            'controller' => ['required','min:2'],
            'field_name' => ['required','min:2'],
        ];
    }
}
