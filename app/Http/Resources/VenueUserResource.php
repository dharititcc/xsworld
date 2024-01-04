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
        return [
            'id'                    => $this->user->id,
            'first_name'            => $this->user->first_name,
            'last_name'             => $this->user->last_name,
            'email'                 => $this->user->email,
            'username'              => $this->user->username,
            'profile_img'           => $this->user->image,
            'membership'            => $this->user->membership['membership'],
            'membership_level'      => $this->user->membership['membership_level'],
            'credit_amount'         => (float) $this->credit_amount ?? 0,
            'points'                => $this->points ?? 0,
            // 'restaurant'            => $this->restaurant,
            // ''
            // 'rating'                => (double) round($this->restaurant->avg_review_rating,1),
            // 'distance'              => (float) $this->restaurant->distance,
        ];
    }
}
