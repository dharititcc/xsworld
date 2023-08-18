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
        // dd($this->attachment_url);
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'category_id'               => $this->category->id,
            'category_name'             => $this->category->name,
            'restaurant_id'             => $this->restaurant->id,
            'restaurant_name'           => $this->restaurant->name,
            'type'                      => $this->type,
            'is_variable'               => $this->is_variable,
            'price'                     => $this->price,
            'currency'                  => $this->restaurant->currency->code,
            'is_featured'               => $this->is_featured,
            'image'                     => $this->attachment_url ?? '',
            'is_favourite'              => $this->count_user_favourite_item
        ];
    }
}
