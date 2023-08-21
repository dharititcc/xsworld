<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'restaurant_id',
        'restaurant_id',
        'status'
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
     * Method attachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    /**
     * Get the restaurant that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get all of the children for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Get all of the items for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(RestaurantItem::class, 'category_id', 'id')->where('type', RestaurantItem::ITEM);
    }

    /**
     * Get all of the mixers for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mixers(): HasMany
    {
        return $this->hasMany(RestaurantItem::class, 'category_id', 'id')->where('type', RestaurantItem::MIXER);
    }

    /**
     * Get all of the addons for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addons(): HasMany
    {
        return $this->hasMany(RestaurantItem::class, 'category_id', 'id')->where('type', RestaurantItem::ADDON);
    }

    /**
     * Method getImageAttribute
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        return isset($this->attachment) ? asset('storage/categories/'.$this->attachment->stored_name) : '';
    }
}
