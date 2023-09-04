<?php

namespace App\Http\Resources;

use App\Models\RestaurantItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = [];
        if( $this->type == RestaurantItem::ITEM )
        {
            $response = [
                'id'               => $this->id,
                'name'             => $this->restaurant_item->name,
                'quantity'         => $this->quantity,
                'price'            => $this->price,
                'total'            => $this->total,
                'type'             => $this->restaurant_item->item_type,
                'variation'        => isset($this->variation_id) ? [
                    'id'        => $this->id,
                    'name'      => $this->variation->name,
                    'price'     => $this->price,
                    'quantity'  => $this->quantity
                ] : [],
                'addons'            => isset($this->addons) ? OrderItemAddonResource::collection($this->addons) : [],
                'mixer'             => isset($this->mixer) ? new OrderItemMixerResource($this->mixer) : []
            ];
        }

        return $response;
    }
}
