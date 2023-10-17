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
            'quantity'              => (int) $this->quantity,
            'price'                 => $this->price,
            'currency'              => $this->restaurant_item->restaurant->currency->code,
            'symbol'                => $this->restaurant_item->restaurant->country->symbol,
            'total'                 => $this->total
        ];
    }
}
