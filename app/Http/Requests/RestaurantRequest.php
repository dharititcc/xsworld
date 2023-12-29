<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
            'name'          => 'required|string|max:20',
            'email'         => 'required|unique:users,email',
            'phone'         => 'required|digits:10|unique:users,phone',
            'street1'       => 'required',
            'first_name'    => 'required|alpha',
            'city'          => 'required|alpha',
            'password'      => 'required',
            'image'         => 'required|image|mimes:png,jpg,jpeg',
            'state'         => 'required|alpha',
        ];
    }
}
