<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BarOrderListingResource extends JsonResource
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
            'user'                      => $this->user->name ?? null,
            'user_image'                => $this->user->image ?? null,
            'pickup_point'              => isset($this->pickup_point->id) ? $this->pickup_point->id : '',
            'pickup_point_name'         => isset($this->pickup_point->id) ? $this->pickup_point->name : '',
            'pickup_point_user'         => $this->pickup_point_user->name ?? '',
            'pickup_point_user_image'   => isset($this->pickup_point->id) ? $this->pickup_point->image : '',
            'status'                    => $this->order_splits[0]->status_name,
            'status_no'                 => $this->order_splits[0]->status,
            'table_no'                  => 0,
            'apply_time'                => $this->apply_time ?? 0,
            'last_delayed_time'         => $this->last_delayed_time*60 ?? 0,
            'remaining_time'            => $this->remainingtime,
            'remaining_date'            => isset($this->remaining_date) ? $this->remaining_date : '',
            'created_date'              => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date'              => Carbon::parse($this->updated_at)->toDateTimeString(),
            'order_items'               => isset($this->order_items) ? OrderItemResource::collection($this->order_items) : [],
        ];
    }
}
