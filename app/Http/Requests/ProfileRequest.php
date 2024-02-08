<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();

        return [
            'full_name'             => 'required|string',
            'email'                 => 'required|unique:users,email,'.$user->id,
            'birth_date'            => 'required|date_format:Y-m-d',
            'platform'              => 'nullable',
            'os_version'            => 'nullable',
            'application_version'   => 'nullable',
            'model'                 => 'nullable'
        ];
    }
}
