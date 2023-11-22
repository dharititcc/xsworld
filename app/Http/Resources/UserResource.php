<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                    => $this->id,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'email'                 => $this->email,
            'username'              => $this->username,
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
            'credit_amount'         => (float) $this->credit_amount ?? 0,
            'points'                => $this->points ?? 0,
            'email_verified_at'     => $this->email_verified_at ?? "",
            'is_mobile_verify'      => (int) $this->is_mobile_verify ?? 0,
            'created_at'            => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
