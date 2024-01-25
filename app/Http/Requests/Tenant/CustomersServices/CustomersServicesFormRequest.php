<?php

namespace App\Http\Requests\Tenant\CustomersServices;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomersServicesFormRequest extends FormRequest
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
           'selectedCustomer.required' => __("You must select the customer!"),
           'selectedService.required' => __("You must select the service!"),
           'start_date.required' => __("You must select a start date."),
           'end_date.required' => __("You must select a end date."),
           'type.required' => __("You must select a type."),
           'selectedLocation.required' => __("You must select the customer location!"),
           'selectedCustomer.min' => __("The customer must be at least 1 character."),
           'selectedService.min' => __("The service must be at least 1 character."),
           'selectedLocation.min' => __("The location must be at least 1 character."),
           'selectedTypeContract.required' => __("You must select a contract time"),
           'number_times.required' => __("You must select a number of times"),
           'new_date.required' => __("You must select a initialization date"),
           'time_repeat.required' => __("You must select a time to repeat")
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
            'selectedCustomer' => ['required', 'min:1'],
            'selectedService' => ['required', 'min:1'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'type' => ['required'],
            'selectedLocation' => ['required', 'min:1'],
            'selectedTypeContract' => ['required'],
            'number_times' => ['required'],
            'new_date' =>['required'],
            'time_repeat' => ['required']
         ];
    }
}
