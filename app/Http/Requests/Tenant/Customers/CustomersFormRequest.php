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
          'slug.required' => __("The slug field is required."),
          'slug.min' => __("The slug must be at least 2 characters."),
          'short_name.required' => __("The short name field is required."),
          'short_name.min' => __("The short name must be at least 2 characters."),
          'username.required' => __("The username field is required"),
          'vat.required' => __("The vat field is required."),
          'vat.min' => __("The vat must be at least 9 characters."),
          'vat.max' => __("The vat must not be greater than 9 characters."),
          'contact.required' => __("The contact field is required."),
          'contact.min' => __("The contact must be at least 9 characters."),
          'email.required' => __("The email field is required."),
          'email.email' => __("The email must be a valid email address."),
          'address.required' => __("The address field is required."),
          'address.min' => __("The address must be at least 5 characters."),
          'zipcode.required' => __("The zipcode field is required."),
          'zipcode.min' => __("The zipcode must be at least 5 characters."),
          'district.required' => __("The district field is required."),
          'county.required' => __("The county field must be at least 1 character."),
          'account_manager' => __("The account manager field must be at least 1 character.")
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
            'slug' => ['required', 'min:2'],
            'short_name' => ['required', 'min:2'],
            'username' => ['required'],
            'vat' => ['required', 'min:9','max:9'],
            'contact' => ['required', 'min:9'],
            'email' => ['required', 'email'],
            'address' => ['required', 'min:5'],
            'zipcode' => ['required', 'min:5'],
            'district' => ['required'],
            'county' => ['required'],
            'account_manager' => ['required']
        ];
    }
}
