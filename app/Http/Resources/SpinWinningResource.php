<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpinWinningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount'    => in_array($this->type, [User::FIVE_X, User::TEN_X]) ? 5 : 2.5,
            'type'      => $this->type,
            'created_at'=> Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
