<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantItem extends Model
{
    use HasFactory;

    protected $table = 'restaurant_items';

    /** featured */
    const FEATURED = 1;

    /** item type */
    const SIMPLE    = 0;
    const VARIABLE  = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'restaurant_id',
        'item_id',
        'price',
        'is_featured',
        'variation',
        'type',
        'restaurant_item_id'    // FK (Addon/Mixers of specific item / specific restaurant)
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the restaurant that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get the item that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * Get the restaurant_item that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_item(): BelongsTo
    {
        return $this->belongsTo(self::class, 'restaurant_item_id', 'id');
    }

    /**
     * Get all of the restaurant_items for the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurant_items(): HasMany
    {
        return $this->hasMany(self::class, 'restaurant_item_id', 'id');
    }
}
