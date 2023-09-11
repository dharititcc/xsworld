<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'restaurant_id'             => $this->restaurant->id,
            'pickup_point'              => isset($this->pickup_point->id) ? $this->pickup_point->id : '',
            'pickup_point_user'         => $this->pickup_point_user->name ?? '',
            'pickup_point_user_image'   => isset($this->pickup_point->id) ? $this->pickup_point->image : '',
            'amount'                    => $this->amount,
            'total'                     => number_format($this->total, 2),
            'status'                    => $this->order_status,
            'user_payment_method'       => '',
            'credit_point'              => $this->credit_point,
            'created_date'              => $this->created_at,
            'progress'                  => $this->progress,
            'currency'                  => $this->restaurant->currency->name,
            'order_items'               => isset($this->order_items) ? OrderItemResource::collection($this->order_items) : [],
            'pickup_points'             => RestaurantPickupPointResources::collection($this->restaurant->pickup_points)
        ];
    }
}
