<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->payment_method);
        return [
            'id'                        => $this->id,
            'restaurant_name'           => $this->restaurant->name,
            'restaurant_id'             => $this->restaurant->id,
            'pickup_point'              => $this->pickup_point,
            'pickup_point_user'         => $this->pickup_point_user->name,
            'amount'                    => $this->amount,
            'total'                     => $this->total,
            'status'                    => $this->order_status,
            'user_payment_method'       => '',
            'credit_point'              => $this->credit_point,
            'order_items'               => isset($this->items) ? OrderItemResource::collection($this->items) : []
        ];
    }
}
