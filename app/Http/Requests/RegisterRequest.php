<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        return [
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'email'                 => 'required|unique:users,email',
            'password'              => 'required|string|min:6',
            'phone'                 => 'required|unique:users,phone',
            'birth_date'            => 'required|date_format:Y-m-d',
            'platform'              => 'required',
            'os_version'            => 'required',
            'application_version'   => 'required',
            'model'                 => 'required',
            'registration_type'     => [
                                            'required',
                                            Rule::in(User::EMAIL, User::PHONE, User::GOOGLE, User::FACEBOOK)
                                        ]
        ];
    }
}
