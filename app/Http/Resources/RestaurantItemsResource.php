<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantItemsResource extends JsonResource
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
            'id'                        => $this->id,
            'restaurant_item_type_id'   => $this->restaurant_item_type->item_type_id,
            'item_type_name'            => $this->restaurant_item_type->item_type->name,
            'item_name'                 => $this->item->name,
            'price'                     => $this->price,
            'image'                     => $this->attachment_url,
            'quantity'                  => $this->quantity,
        ];
    }
}
