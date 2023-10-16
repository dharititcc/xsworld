<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'phone'                 => 'required|unique:users,phone',
            'birth_date'            => 'required|date_format:Y-m-d',
            'platform'              => 'required',
            'os_version'            => 'required',
            'application_version'   => 'required',
            'model'                 => 'required',
            'country'               => 'required',
            'country_code'          => 'required',
            'fcm_token'             => 'required',
            'registration_type'     => [
                                            'required',
                                            Rule::in(User::EMAIL, User::PHONE, User::GOOGLE, User::FACEBOOK,User::APPLE)
                                        ]
        ];

        if (request()->registration_type == User::APPLE)
        {
            $rules['social_id']      = 'required';
        }

        if( in_array(request()->registration_type, [ User::GOOGLE, User::FACEBOOK, User::EMAIL]))
        {
            $rules['email'] = 'required|email';
        }
        return $rules;
    }
}
