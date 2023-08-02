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
            'id'                    => $this->id,
            'restaurant_item_type'  => $this->restaurant_item_type_id ,
            // 'restaurant_item_type'  => isset($this->restaurant_item_type_id) ? RestaurantItemTypes::collection($this->restaurant_item_type_id) : [],
        ];
    }
}
