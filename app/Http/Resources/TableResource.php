<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id'                  => $this->table_order->id,
            'status'                    => $this->table_order->order_status,
            'status_no'                 => (int) $this->table_order->status,
            'table_no'                  => $this->restaurant_table->id ?? '',
            'table_name'                => $this->restaurant_table->name ?? '',
            'restaurant_name'           => $this->table_order->restaurant->name,
            'user'                      => $this->user->name,
            'user_image'                => $this->user->image,
            'user_id'                   => $this->user->id,
            'restaurant_id'             => $this->table_order->restaurant->id,
            'order_items'               => ($this->table_order->type != 1) ? OrderItemResource::collection($this->table_order->order_items) : [],
        ];
    }
}
