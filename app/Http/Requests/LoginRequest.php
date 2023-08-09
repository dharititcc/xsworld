<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class loginRequest extends FormRequest
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
            'password'              => 'required|string|min:6',
            'fcm_token'             => 'required|string',
            'platform'              => 'required',
            'os_version'            => 'required',
            'application_version'   => 'required',
            'model'                 => 'required',
            'registration_type'     => [
                                            'required',
                                            Rule::in(User::EMAIL, User::PHONE, User::GOOGLE, User::FACEBOOK)
                                        ]
        ];

        if( request()->registration_type == User::EMAIL )
        {
            $rules['email'] = 'required|email';
        }

        if( request()->registration_type == User::PHONE )
        {
            $rules['phone'] = 'required';
        }
    }
}
