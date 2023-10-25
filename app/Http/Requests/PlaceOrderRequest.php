<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
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
            'order_id'          => 'required',
            'credit_amount'     => 'required',
            'amount'            => 'required',
            'card_id'           => 'nullable',
            'pickup_point_id'   => 'nullable',
            'table_id'          => 'nullable'
        ];
    }
}
