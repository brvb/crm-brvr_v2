<?php

namespace App\Http\Requests\Tenant\TeamMember;

use App\Models\Tenant\TeamMember;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TeamMemberFormRequest extends FormRequest
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
          'name.required' => __("The name field is required."),
          'name.min' => __("The name must be at least 2 characters."),
          'username.required' => __("The username field is required."),
          //'username.unique' => __("That username already exists"),
          //'email.unique' => __("That email already exists."),
          'mobile_phone.required' => __("The mobile phone field is required."),
          'mobile_phone.min' => __("The mobile phone must be at least 9 characters."),
          'job.required' => __("The job field is required."),
          'job.min' => __("The job field must be at least 1 character."),
          'color.required' => __("The color of team member field is required.")
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $idVal = TeamMember::where('id',$this->idTeamMember)->first();
        // Rule::unique('users')->ignore($this->user()->id, 'id');


        return [
              'name' => ['required', 'min:2'],
              'username' => ['required'],
             // 'email' => ['unique:team_members,email'],
              'mobile_phone' => ['required', 'min:9'],
              'job' => ['required', 'min:1'],
              'color' => ['required']
            ];

    }
}
