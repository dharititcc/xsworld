<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantItemType extends Model
{
    use HasFactory;

    /** @var string $table */
    protected $table = 'restaurant_item_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'restaurant_id',
        'item_type_id'
    ];

    /**
     * Get the item_type that owns the RestaurantItemType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item_type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'id');
    }

    /**
     * Get the restaurant that owns the RestaurantItemType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
