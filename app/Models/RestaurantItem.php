<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    /**
     * The restaurant_item_variations that belong to the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurant_item_variations(): BelongsToMany
    {
        return $this->belongsToMany(Variation::class, 'restaurant_item_variations', 'restaurant_item_id', 'variation_id');
    }

    /**
     * Method attachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    /**
     * Method getAttachmentUrlAttribute
     *
     * @return string
     */
    public function getAttachmentUrlAttribute(): string
    {
        if( $this->attachment )
        {
            return asset('storage/items/'.$this->attachment->stored_name);
        }
        else
        {
            return asset('storage/restaurants/'.$this->item->attachment->stored_name);
        }
    }

    /**
     * Get the restaurant_item_type that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_item_type(): BelongsTo
    {
        return $this->belongsTo(RestaurantItemType::class, 'restaurant_item_type_id', 'id');
    }
}
