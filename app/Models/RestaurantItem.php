<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\ErrorHandler\Collecting;

class RestaurantItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'restaurant_items';

    /** featured */
    const FEATURED = 1;

    /** item types */
    const ADDON     = 1;
    const ITEM      = 2;
    const MIXER     = 3;

    const ITEM_TYPES = [
        self::ADDON => 'Addon',
        self::ITEM  => 'Item',
        self::MIXER => 'Mixer',
    ];

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
        'description',
        'restaurant_id',
        'category_id',
        'price',
        'quantity',
        'is_featured',
        'is_variable',
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['attachment'];

    /**
     * Method getItemTypeAttribute
     *
     * @return string
     */
    public function getItemTypeAttribute(): string
    {
        return self::ITEM_TYPES[$this->type];
    }

    /**
     * Get the category that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

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
        $url = '';
        if( $this->attachment )
        {
            $url = asset('storage/items/'.$this->attachment->stored_name);
        }

        return $url;
    }

    /**
     * Method getCountUserFavouriteItemAttribute
     *
     * @return int
     */
    public function getCountUserFavouriteItemAttribute(): Collection
    {
        $user = auth()->check() ? auth()->user() : null;

        if( isset( $user->id ) )
        {
            return $user->favourite_items()->where('user_favourite_items.restaurant_item_id', $this->id)->get();
        }

        return false;
    }
     /**
     * Query builder scope to search on text
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $search      Search term
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray)
            {
            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search)
            {
                foreach ($search as $search)
                {
                $query->where('restaurant_items.name', 'like', "%" . $search . "%")
                    ->orWhere('restaurant_items.description', 'like', "%" . $search . "%")
                    ->orWhere('restaurant_items.price', 'like', "%" . $search . "%");
                }
            });
        }
    }
}
