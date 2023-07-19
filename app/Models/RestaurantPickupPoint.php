<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantPickupPoint extends Model
{
    use HasFactory;

    protected $table = 'restaurant_pickup_points';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'restaurant_id',
        'pickup_point_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the restaurant that owns the RestaurantPickupPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'is');
    }

    /**
     * Get the user that owns the RestaurantPickupPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the pickup_point that owns the RestaurantPickupPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pickup_point(): BelongsTo
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id', 'id');
    }
}
