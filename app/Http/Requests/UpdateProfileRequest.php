<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $user = auth()->user();

        return [
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'phone'                 => 'required|unique:users,phone,'.$user->id,
            'email'                 => 'required|unique:users,email,'.$user->id,
            'country_code'          => 'required',
            'birth_date'            => 'required|date_format:Y-m-d',
            'profile_image'         => 'image|mimes:jpeg,png,jpg|max:10240'
        ];
    }
}
