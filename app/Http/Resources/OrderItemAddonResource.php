<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemAddonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            // 'order_id'              => $this->order_id,
            'restaurant_item_id'    => $this->restaurant_item_id,
            'name'                  => $this->restaurant_item->name,
            'quantity'              => $this->quantity,
            'price'                 => $this->price,
            'total'                 => $this->total
        ];
    }
}
