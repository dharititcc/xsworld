<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'item_type_id'
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
     * Get the drink_type that owns the DrinkType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drink_type(): BelongsTo
    {
        return $this->belongsTo(self::class, 'item_type_id', 'id');
    }

    /**
     * Method getImageAttribute
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        return isset($this->attachment) ? asset('storage/item_types/'.$this->attachment->stored_name) : '';
    }

    /**
     * Get all of the items for the ItemType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'item_type_id', 'id');
    }
}
