<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd((double) round($this->restaurant->avg_review_rating,1));
        // dd($this->current_membership_points);
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
            'friendship'            => isset($this->friend) ? $this->friend->status : 3,
            'member_id'             => $this->id,
            // 'restaurant'            => $this->restaurant,
            // ''
            // 'rating'                => (double) round($this->restaurant->avg_review_rating,1),
            // 'distance'              => (float) $this->restaurant->distance,
        ];
    }
}
