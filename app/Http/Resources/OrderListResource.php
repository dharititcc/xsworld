<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'restaurant_name'           => $this->restaurant->name,
            'user'                      => $this->user->name,
            'user_id'                   => $this->user_id,
            'restaurant_id'             => $this->restaurant->id,
            'table_no'                  => $this->restaurant_table->id ?? 0,
            'table_name'                => $this->restaurant_table->code ?? '',
            'amount'                    => $this->amount,
            'total'                     => number_format($this->total, 2),
            'status'                    => $this->order_status,
            'status_no'                 => (int) $this->status,
            'isFoodAvl'                 => $this->order_category_type,
            'apply_time'                => $this->apply_time ?? 0,
            'created_date'              => Carbon::parse($this->created_at)->toDateTimeString(),
            'progress'                  => $this->progress ?? 0,
            'remaining_time'            => $this->remainingtime,
            'place_at'                  => $this->place_at ?? '',
            'remaining_date'            => isset($this->remaining_date) ? $this->remaining_date : '',
            'currency'                  => $this->restaurant->currency->code,
            'symbol'                    => $this->restaurant->country->symbol,
            'rated'                     => $this->reviews->count(),
            'items_count'               => $this->order_items->count(),
            'restaurant_image'          => $this->restaurant->image,
        ];
    }
}
