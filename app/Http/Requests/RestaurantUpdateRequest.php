<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantUpdateRequest extends FormRequest
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
            'email'         => 'required|unique:users,email,'.$this->id,
            'phone'         => 'required|digits:10|unique:users,phone,'.$this->id,
            'street1'       => 'required',
            'first_name'    => 'required',
            'city'          => 'required|string',
            'image'         => 'required',
            'state'         => 'required|string',
        ];
    }
}
