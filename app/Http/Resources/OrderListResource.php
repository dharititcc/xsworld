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
            'table_no'                  => $this->restaurant_table->id ?? '',
            'table_name'                => $this->restaurant_table->name ?? '',
            'amount'                    => $this->amount,
            'total'                     => number_format($this->total, 2),
            'status'                    => $this->order_status,
            'status_no'                 => (int) $this->status,
            'apply_time'                => $this->apply_time ?? 0,
            'created_date'              => Carbon::parse($this->created_at)->toDateTimeString(),
            'progress'                  => $this->progress ?? 0,
            'currency'                  => $this->restaurant->currency->code,
            'symbol'                    => $this->restaurant->country->symbol,
            'rated'                     => $this->reviews->count(),
        ];
    }
}
