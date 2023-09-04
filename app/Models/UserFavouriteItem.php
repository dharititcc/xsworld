<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavouriteItem extends Model
{
    use HasFactory;

    /** @var string $table */
    protected $table = 'user_favourite_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'restaurant_item_id',
    ];

    /**
     * Get the restaurant that owns the UserFavouriteItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(RestaurantItem::class, 'restaurant_item_id', 'id');
    }

}
