<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /**
     * Get the restaurant that owns the RestaurantTime
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get the day associated with the RestaurantTime
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function day(): belongsTo
    {
        return $this->belongsTo(Day::class, 'days_id', 'id');
    }
}
