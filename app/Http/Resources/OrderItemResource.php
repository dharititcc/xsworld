<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this);
        return [
            'id'               => $this->id,
            'name'             => $this->restaurant_item->name,
            'quantity'         => $this->quantity,
            'price'            => $this->price,
            'total'            => $this->total,
            'type'             => $this->restaurant_item->item_type
        ];
    }
}
