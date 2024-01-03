<?php

namespace App\Http\Resources;

use App\Models\CustomerTable;
use App\Models\Order;
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
            'order_id'                  => isset($this->order_id) ? $this->order_id : 0,
            'status'                    => isset($this->waiter_status_name) ? $this->waiter_status_name : $this->order_status,
            'status_no'                 => isset($this->waiter_status_name) ? (int) $this->waiter_status : CustomerTable::AWAITING_SERVICE,
            'table_no'                  => $this->restaurant_table->id ?? $this->restaurant_table_id,
            'table_name'                => $this->restaurant_table->code ?? '',
            'order_type'                => isset($this->table_order->type) ? $this->table_order->type : 0,
            'restaurant_name'           => isset($this->table_order->restaurant->name) ? $this->table_order->restaurant->name : '',
            'user'                      => $this->user->name,
            'user_image'                => $this->user->image,
            'user_id'                   => $this->user->id,
            'restaurant_id'             => isset($this->restaurant->id) ? $this->restaurant->id : 0,
            'total_items'               => isset($this->order_items) ? $this->order_items->sum('quantity') : 0,
            'order_items'               => isset($this->order_items) ? OrderItemResource::collection($this->order_items) : []
        ];
    }
}
