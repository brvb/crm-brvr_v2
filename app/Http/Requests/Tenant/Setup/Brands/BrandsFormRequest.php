<?php

namespace App\Http\Requests\Tenant\Setup\Brands;

use Illuminate\Foundation\Http\FormRequest;

class BrandsFormRequest extends FormRequest
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
            'name.min' => __("The description must be at least 2 characters.")
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
        ];
    }
}
