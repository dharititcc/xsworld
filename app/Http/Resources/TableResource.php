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
        $status  = "";
        if(isset($this->table_order->order_status)) {
            if($this->table_order->status == Order::CURRENTLY_BEING_PREPARED) {
                $status     = "Order #".$this->table_order->id.  $this->table_order->order_status;
            } else if($this->table_order->status == Order::KITCHEN_CONFIRM) {
                $status     = "Currently being served";
            } else if($this->table_order->status == Order::READYFORPICKUP) {
                $status     = $this->table_order->order_status;
            } else {
                $status     = Order::IDLE;
            }
        }
        return [
            'order_id'                  => isset($this->table_order->id) ? $this->table_order->id : 0,
            'status'                    => isset($this->table_order->order_status) ? $status : $this->order_status,
            'status_no'                 => isset($this->table_order->status) ? (int) $this->table_order->status : CustomerTable::AWAITING_SERVICE,
            'table_no'                  => $this->restaurant_table->id ?? $this->restaurant_table_id,
            'table_name'                => $this->restaurant_table->code ?? '',
            'order_type'                => isset($this->table_order->type) ? $this->table_order->type : 0,
            'restaurant_name'           => isset($this->table_order->restaurant->name) ? $this->table_order->restaurant->name : '',
            'user'                      => $this->user->name,
            'user_image'                => $this->user->image,
            'user_id'                   => $this->user->id,
            'restaurant_id'             => isset($this->table_order->restaurant->id) ? $this->table_order->restaurant->id : 0,
            'total_items'               => isset($this->table_order->id) ? $this->table_order->order_items->count() : 0,
            'order_items'               => isset($this->table_order->id) && $this->table_order->type != 1 ? OrderItemResource::collection($this->table_order->order_items) : []
        ];
    }
}
