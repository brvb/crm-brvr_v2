<?php

namespace App\Http\Requests\Tenant\Customers;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomersFormRequest extends FormRequest
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
          'name.min' => __("The name must be at least 2 characters."),
          'short_name.required' => __("The short name field is required."),
          'short_name.min' => __("The short name must be at least 2 characters."),
          'vat.required' => __("The vat field is required."),
          'vat.min' => __("The vat must be at least 9 characters."),
          'vat.max' => __("The vat must not be greater than 9 characters."),
          'contact.required' => __("The contact field is required."),
          'contact.min' => __("The contact must be at least 9 characters."),
          'address.required' => __("The address field is required."),
          'address.min' => __("The address must be at least 5 characters."),
          'zipcode.required' => __("The zipcode field is required."),
          'zipcode.min' => __("The zipcode must be at least 5 characters."),
          'county.required' => __("The county field must be at least 1 character."),
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
            'short_name' => ['required', 'min:2'],
            'vat' => ['required', 'min:9','max:9'],
            'contact' => ['required', 'min:9'],
            'address' => ['required', 'min:5'],
            'zipcode' => ['required', 'min:5'],
            'county' => ['required'],
        ];
    }
}
