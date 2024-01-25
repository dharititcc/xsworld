<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyFriendsResource extends JsonResource
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
            'profile_img'           => $this->image,
            'membership'            => $this->membership['membership'],
            'membership_level'      => $this->membership['membership_level'],
            'credit_amount'         => (float) $this->credit_amount ?? 0,
            'points'                => $this->points ?? 0,
            'friendship'            => $this->fr,
            // 'myfriend'              => isset($this->friends) ? $this->friends : [],
            'member_id'             => $this->id,
        ];
    }
}
