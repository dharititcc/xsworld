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
            'id'            => $this->id,
            'name'          => $this->full_name,
            'email'         => $this->email,
            'phone'         => $this->phone ?? '',
            'phone2'        => $this->phone2 ?? '',
            'country_id'    => $this->country_id ?? '',
            'country_name'  => $this->country->name ?? '',
            'country_code'  => $this->country_code ?? '',
            'created_at'    => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
