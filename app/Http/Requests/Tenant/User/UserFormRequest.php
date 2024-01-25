<?php

namespace App\Http\Requests\Tenant\User;

use App\Models\Tenant\TeamMember;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
     * Messages to display to the user
     *
     * @return void
     */
    public function messages()
    {
        return [
          'username.required' => __("The username field is required."),
          'username.unique' => __("That username already exists"),
          'password.required' => __("The password field is required."),
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
              'username' => ['required',Rule::unique('users')->ignore($this->user()->id, 'id')],
              'password' => ['required']
            ];
    }
}
