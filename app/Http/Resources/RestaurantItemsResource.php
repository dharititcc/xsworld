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
            'description'               => $this->description ?? '',
            'category_id'               => $this->category->id,
            'category_name'             => $this->category->name,
            'restaurant_id'             => $this->restaurant->id,
            'restaurant_name'           => $this->restaurant->name,
            'item_type'                 => $this->item_type,
            'is_variable'               => $this->is_variable,
            'price'                     => $this->price,
            'currency'                  => $this->restaurant->currency->code,
            'is_featured'               => $this->is_featured,
            'image'                     => $this->attachment_url ?? '',
            'is_favourite'              => $this->count_user_favourite_item,
            'variations'                => isset($this->variations) ? VariationResource::collection($this->variations) : [],
            'mixers'                    => isset($this->category->mixers) ? AddonMixerResource::collection($this->category->mixers) : [],
            'addons'                    => isset($this->category->addons) ? AddonMixerResource::collection($this->category->addons) : [],
        ];
    }
}
