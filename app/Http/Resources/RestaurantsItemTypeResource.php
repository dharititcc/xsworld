<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantsItemTypeResource extends JsonResource
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
            'name'              => $this->item_type->name,
            // 'latitude'          => $this->latitude,
            // 'longitude'         => $this->longitude,
            // 'address'           => $this->address,
            // 'phone'             => $this->phone ?? '',
            // 'specialisation'    => $this->specialisation,
            // 'distance'          => $this->distance,
            // 'image'             => $this->image,
            // 'rating'            => 4.5,
            // 'item_types'        => isset($this->item_types) ? RestaurantItemTypesResources::collection($this->item_types) : [],
        ];
    }
}
