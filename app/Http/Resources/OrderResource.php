<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

class OrderResource extends JsonResource
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
            'user_image'                => $this->user->image,
            'restaurant_id'             => $this->restaurant->id,
            'pickup_point'              => isset($this->pickup_point->id) ? $this->pickup_point->id : '',
            'pickup_point_name'         => isset($this->pickup_point->id) ? $this->pickup_point->name : '',
            'pickup_point_user'         => $this->pickup_point_user->name ?? '',
            'pickup_point_user_image'   => isset($this->pickup_point->id) ? $this->pickup_point->image : '',
            'amount'                    => $this->amount,
            'total'                     => number_format($this->total, 2),
            'status'                    => $this->order_status,
            'status_no'                 => $this->status,
            'user_payment_method'       => '',
            'credit_point'              => $this->credit_point,
            'apply_time'                => $this->apply_time ?? 0,
            'created_date'              => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_date'              => Carbon::parse($this->updated_at)->toDateTimeString(),
            'remaining_time'            => $this->remainingtime,
            'progress'                  => $this->progress ?? 0,
            'completion_time'           => $this->completiontime ?? '',
            'served_time'               => $this->servedtime ?? '',
            'currency'                  => $this->restaurant->currency->code,
            'symbol'                    => $this->restaurant->country->symbol,
            'card_id'                   => $this->card_id ?? '',
            'charge_id'                 => $this->charge_id ?? '',
            'total_items'               => $this->order_items->sum('quantity'),
            'order_items'               => isset($this->order_items) ? OrderItemResource::collection($this->order_items) : [],
            'pickup_points'             => PickUpPointResource::collection($this->restaurant->pickup_points),
            'card_details'              => isset($this->carddetails) ? $this->carddetails : new stdClass
        ];
    }
}
