<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    /** order item types */
    const ADDON = 0;
    const ITEM  = 1;
    const Mixer = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'restaurant_item_id',
        'variation_id',
        'parent_item_id',
        'quantity',
        'price',
        'total',
        'type'
    ];

    /**
     * Get the order that owns the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Get the restaurant_item that owns the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_item(): BelongsTo
    {
        return $this->belongsTo(RestaurantItem::class, 'restaurant_item_id', 'id');
    }

    /**
     * Method scopeAddon
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAddon(Builder $query): Builder
    {
        return $query->where('type', self::ADDON);
    }

    /**
     * Method scopeItem
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeItem(Builder $query): Builder
    {
        return $query->where('type', self::ITEM);
    }

    /**
     * Method scopeMixer
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMixer(Builder $query): Builder
    {
        return $query->where('type', self::Mixer);
    }
}
