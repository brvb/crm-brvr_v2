<?php

namespace App\Http\Requests\Tenant\CustomerContacts;

use Illuminate\Foundation\Http\FormRequest;

class CustomerContactsFormRequest extends FormRequest
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
            'name.required' => __("The name field is required."),
            'name.min' => __("The name field must be at least 1 character."),
            'mobile_phone.required_without_all' => __("The mobile phone field is required when none of landline / email are present."),
            'landline.required_without_all' => __("The landline field is required when none of mobile phone / email are present."),
            'email.required_without_all' => __("The email field is required when none of mobile phone / landline are present.")
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
            'name' => ['required', 'min:1'],
            'job_description' => [],
            'mobile_phone' => ['required_without_all:landline,email'],
            'landline' => ['required_without_all:mobile_phone,email'],
            'email' => ['required_without_all:mobile_phone,landline']
        ];
    }
}
