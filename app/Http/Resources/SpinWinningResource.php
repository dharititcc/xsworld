<?php

namespace App\Http\Resources;

use App\Models\Spin;
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
            'amount'    => (float) in_array($this->type, [Spin::FIVE_X, Spin::TEN_X]) ? 5 : 2.5,
            'type'      => $this->spin_type,
            'created_at'=> Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
