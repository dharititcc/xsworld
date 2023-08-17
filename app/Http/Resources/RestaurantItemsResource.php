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
            'name'                      => $this->name,
            'category_id'               => $this->category_id,
            'category_name'             => $this->category->name,
            'restaurant_id'             => $this->restaurant_id,
            'restaurant_name'           => $this->restaurant->name,
            'type'                      => $this->type,
            'is_variable'               => $this->is_variable,
            'price'                     => $this->price,
            'is_featured'               => $this->is_featured
        ];
    }
}
