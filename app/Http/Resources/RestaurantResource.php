<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'address'           => $this->address,
            'phone'             => $this->phone ?? '',
            'specialisation'    => $this->specialisation,
            'distance'          => $this->distance,
            'image'             => $this->image,
            'rating'            => 4.5,
            'categories'        => isset($this->main_categories) ? CategoryResource::collection($this->main_categories) : [],
            'pickup_points'     => isset($this->pickup_points) ? PickUpPointResource::collection($this->pickup_points) : [],
            // 'featured_items'    => RestaurantItemsResource::collection($this->featured_items),
        ];
    }
}
