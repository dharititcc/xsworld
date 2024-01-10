<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarPickupAddUpdateRequest extends FormRequest
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
        return [
            'password'      => 'required|min:8',
            'barpick_id'    => 'required|regex:/^\S*$/u|unique:users,username,'
        ];
    }

         /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'barpick_id.regex' => 'The :attribute must not include empty spaces.',
        ];
    }
}
