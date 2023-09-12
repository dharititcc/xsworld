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
            'phone'                 => $this->phone ?? '',
            'phone2'                => $this->phone2 ?? '',
            'country'               => $this->country ? $this->country->name : '',
            'address'               => $this->address ?? '',
            'country_code'          => $this->country_code ?? '',
            'sstripe_customer_id'   => $this->stripe_customer_id ?? '',
            'birth_date'            => $this->birth_date ?? '',
            'profile_img'           => $this->image,
            'member_id'             => $this->id,
            'created_at'            => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
