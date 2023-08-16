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

    /** item types */
    const ADDON     = 1;
    const ITEM      = 2;
    const MIXER     = 3;

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
        'restaurant_item_id',
        'price',
        'quantity',
        'is_featured',
        'variation',
        'type',
        'parent_id'    // FK (Addon/Mixers of specific item / specific restaurant)
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
     * The variations that belong to the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(RestaurantVariation::class, 'restaurant_item_id', 'id');
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
     * Method getCountUserFavouriteItemAttribute
     *
     * @return int
     */
    public function getCountUserFavouriteItemAttribute(): int
    {
        $user = auth()->check() ? auth()->user() : null;

        if( isset( $user->id ) )
        {
            return $user->favourite_items()->where('user_favourite_items.restaurant_item_id', $this->id)->count();
        }

        return false;
    }
}
