<?php

namespace App\Http\Resources;

use App\Models\RestaurantItem;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

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
                'quantity'         => (int) $this->quantity,
                'price'            => $this->price,
                'currency'         => $this->restaurant_item->restaurant->currency->code,
                'symbol'           => $this->restaurant_item->restaurant->country->symbol,
                'total'            => $this->total,
                'type'             => $this->restaurant_item->item_type,
                'variation'        => isset($this->variation) ? [
                    'id'        => $this->id,
                    'name'      => $this->variation->name,
                    'price'     => $this->price,
                    'currency'  => $this->restaurant_item->restaurant->currency->code,
                    'symbol'    => $this->restaurant_item->restaurant->country->symbol,
                    'quantity'  => (int) $this->quantity,
                    'total'     => $this->total
                ] : new stdClass,
                'addons'            => isset($this->addons) ? OrderItemAddonResource::collection($this->addons) : [],
                'mixer'             => isset($this->mixer) ? new OrderItemMixerResource($this->mixer) : new stdClass
            ];
        }

        return $response;
    }
}
