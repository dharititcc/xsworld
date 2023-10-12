<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantFilterApiRequest extends FormRequest
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
            'restaurant_name' => 'nullable|string',
            'drink_name'      => 'nullable|string',
            'distance'        => 'nullable|numeric',
            'latitude'        => 'required',
            'longitude'       => 'required',
            'type'            => 'required',
        ];
    }
}
