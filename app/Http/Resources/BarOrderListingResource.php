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
            // 'restaurant_name'           => $this->restaurant->name,
            // 'restaurant_id'             => $this->restaurant->id,
            'user'                      => $this->user->name,
            'user_image'                => $this->user->image,
            'pickup_point'              => isset($this->pickup_point->id) ? $this->pickup_point->id : '',
            'pickup_point_name'         => isset($this->pickup_point->id) ? $this->pickup_point->name : '',
            'pickup_point_user'         => $this->pickup_point_user->name ?? '',
            'pickup_point_user_image'   => isset($this->pickup_point->id) ? $this->pickup_point->image : '',
            'status'                    => $this->order_status,
            'status_no'                 => $this->status,
            'table_no'                  => 0,
            // 'user_payment_method'       => '',
            // 'credit_amount'              => $this->credit_amount,
            'apply_time'                => $this->apply_time ?? 0,
            'remaining_time'            => $this->remainingtime,
            'created_date'              => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date'               => Carbon::parse($this->updated_at)->toDateTimeString(),
            'order_items'               => isset($this->order_items) ? OrderItemResource::collection($this->order_items) : [],
        ];
    }
}
