<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'phone',
        'specialisation',
        'currency_id'
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
     * Method attachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    /**
     * The owners that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_owners', 'restaurant_id', 'user_id');
    }

    /**
     * The bartenders that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bartenders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_bartenders', 'restaurant_id', 'user_id')->withPivot(['created_at', 'updated_at']);
    }

    /**
     * Get the currency that owns the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * Get all of the categories for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'restaurant_id', 'id');
    }

    /**
     * Get all of the main_categories for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function main_categories(): HasMany
    {
        return $this->hasMany(Category::class, 'restaurant_id', 'id')->whereNull('parent_id');
    }

    /**
     * Get all of the sub_categories for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_categories(): HasMany
    {
        return $this->hasMany(Category::class, 'restaurant_id', 'id')->whereNotNull('parent_id');
    }

    /**
     * The items that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'restaurant_items', 'restaurant_id', 'restaurant_item_id')
        ->withPivot([
            // 'price',
            'is_featured',
            'type',
            // 'variation',
            'restaurant_item_id',
            'restaurant_item_type_id',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);
    }

    /**
     * Method getImageAttribute
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        return isset($this->attachment) ? asset('storage/restaurants/'.$this->attachment->stored_name) : '';
    }

    /**
     * The items that belong to the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pickup_points(): HasMany
    {
        return $this->hasMany(PickupPoint::class, 'restaurant_id', 'id');
    }

    /**
     * Get all of the restaurant_items for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurant_items(): HasMany
    {
        return $this->hasMany(RestaurantItem::class, 'restaurant_id', 'id');
    }

    /**
     * Method getFeaturedItemsAttribute
     *
     * @return Collection
     */
    public function getFeaturedItemsAttribute(): Collection
    {
        return $this->restaurant_items()->where('is_featured', RestaurantItem::FEATURED)->get();
    }
}
