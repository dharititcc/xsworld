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
            'is_variable'               => (int) $this->is_variable,
            'price'                     => $this->price,
            'ingredients'               => $this->ingredients ?? '',
            'country_of_origin'         => $this->country_of_origin ?? '',
            'year_of_production'        => $this->year_of_production ?? '',
            'type_of_drink'             => $this->type_of_drink ?? '',
            'currency'                  => $this->restaurant->currency->code,
            'symbol'                    => $this->restaurant->country->symbol,
            'is_featured'               => (int) $this->is_featured,
            'image'                     => $this->attachment_url ?? '',
            'is_favourite'              => $this->count_user_favourite_item->count() ? 1 : 0,
            'variations'                => isset($this->variations) ? VariationResource::collection($this->variations) : [],
            'mixers'                    => isset($this->category->mixers) ? AddonMixerResource::collection($this->category->mixers) : [],
            'addons'                    => isset($this->category->addons) ? AddonMixerResource::collection($this->category->addons) : [],
        ];
    }
}
