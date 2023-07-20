<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';

    /** item types */
    const ADDON     = 1;
    const ITEM      = 2;
    const MIXER     = 3;

    /** item is variable */
    const VARIABLE  = 1;
    const SIMPLE    = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'item_type_id',
        'type',
        'price'
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
     * Get the item_type that owns the Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item_type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'id');
    }

    /**
     * Get the addon type items
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAddon(Builder $query): Builder
    {
        return $this->scopeItemType($query, self::ADDON);
    }

    /**
     * Get the item type items
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeItem(Builder $query): Builder
    {
        return $this->scopeItemType($query, self::ITEM);
    }

    /**
     * Get the mixer type items
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMixer(Builder $query): Builder
    {
        return $this->scopeItemType($query, self::MIXER);
    }

    /**
     * Get the item type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeItemType(Builder $query, $value): Builder
    {
        return $query->where('type', $value);
    }
}
