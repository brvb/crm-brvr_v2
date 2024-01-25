<?php

namespace App\Http\Requests\Tenant\CustomerLocations;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLocationsFormRequest extends FormRequest
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
            'description.required' => __("The description field is required."),
            'description.min' => __("The name must be at least 5 characters."),
            'selectedCustomer.required' => __("The customer field is required."),
            'selectedCustomer.min' => __("The customer must be at least 1 character."),
            'address.required' => __("The address field is required."),
            'address.min' => __("The address must be at least 5 characters."),
            'zipcode.required' => __("The zipcode field is required."),
            'zipcode.min' => __("The zipcode must be at least 8 characters."),
            'zipcode.max' => __("The zipcode must be at least 8 characters."),
            'contact.required' => __("The contact field is required."),
            'contact.min' => __("The contact must be at least 9 characters."),
            'district.required' => __("The district field is required."),
            'county.required' => __("The county field is required."),
            'county.min' => __("The county must be at least 1 character."),
            'manager_name.required' => __("The name of manager field is required."),
            'manager_name.min' => __("The name of manager must be at least 2 characters."),
            'manager_contact.required' => __("The contact of the manager field is required."),
            'manager_contact.min' => __("The contact of the manager must be at least 9 characters.")
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
            'description' => ['required', 'min:5'],
            'selectedCustomer' => ['required', 'min:1'],
            'address' => ['required','min:5'],
            'zipcode' => ['required','min:8','max:8'],
            'contact' => ['required','min:9'],
            'district' => ['required'],
            'county' => ['required','min:1'],
            'manager_name' => ['required','min:2'],
            'manager_contact' => ['required','min:9']
        ];
    }
}
