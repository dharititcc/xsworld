<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'day'           => $this->day->name,
            'start_time'    => isset($this->start_time) ? $this->start_time : "00:00:00",
            'close_time'    => isset($this->close_time) ? $this->close_time : "00:00:00",
        ];
    }
}
