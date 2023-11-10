<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KitchenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'email'                 => $this->email,
            'username'              => $this->username,
            'restaurant'            => $this->restaurant_kitchen->restaurant,
            // 'restaurant_timming'    => $this->
            'phone'                 => $this->phone ?? '',
            'phone2'                => $this->phone2 ?? '',
            'country'               => $this->country ? $this->country->name : '',
            'symbol'                => $this->country ? $this->country->symbol : '',
            'address'               => $this->address ?? '',
            'country_code'          => $this->country_code ?? '',
            'stripe_customer_id'    => $this->stripe_customer_id ?? '',
            'birth_date'            => $this->birth_date ?? '',
            'profile_img'           => $this->image,
            'member_id'             => $this->id,
            'credit_point'          => (float) $this->credit_points ?? 0,
            'email_verified_at'     => $this->email_verified_at ?? "",
            'is_mobile_verify'      => (int) $this->is_mobile_verify ?? 0,
            'created_at'            => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
