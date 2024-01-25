<?php

namespace App\Http\Requests\Tenant\Setup\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServicesFormRequest extends FormRequest
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
            'name.required' => __("The field name is required."),
            'description.required' => __("The field description is required."),
            'periodicity.required' => __("The field periodicity is required."),
            'name.min' => __("The name must be at least 2 characters."),
            'description.min' => __("The description must be at least 2 characters."),
            'type.required' => __("The field type is required."),
            'payment.required' => __("The field payment is required."),
            'periodicity.min' => __("The periodicity must be at least 1 character."),
            'periodicity.numeric' => __("The periodicity field must be of type numeric.")
            
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
            'name' => ['required', 'min:2'],
            'description' => ['required','min:2'],
            'type' => ['required'],
            'payment' => ['required'],
            'periodicity' => ['required', 'min:1', 'numeric'],
        ];
    }
}
