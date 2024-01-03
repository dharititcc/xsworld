<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantKitchen extends Model
{
    use HasFactory, SoftDeletes;

    /** @var string $table */
    protected $table    = 'restaurant_kitchens';
    protected $guarded  = [];


    public function ScopeRestaurantget($query,$arg)
    {
        return $query->where('restaurant_id',$arg);
    }

    /**
     * Get the user that owns the RestaurantKitchen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the restaurant that owns the RestaurantKitchen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
