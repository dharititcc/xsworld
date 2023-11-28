<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

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
        'category_id',
        'status',
        'quantity',
        'price',
        'total',
        'type'
    ];


     // Customer status
     const PENDNIG                       = 0;
     const ACCEPTED                      = 1;
     const COMPLETED                     = 2;

     const CUSTOMER_CANCELED             = 4;

     // Admin status
     const PARTIAL_REFUND                = 7;
     const FULL_REFUND                   = 8;
 
     const RESTAURANT_CANCELED           = 3;
 
     const ORDER_STATUS = [
         self::PENDNIG                   => 'Pending',
         self::ACCEPTED                  => 'Accepted',
         self::COMPLETED                 => 'Completed',
         self::RESTAURANT_CANCELED       => 'Cancelled',
         self::PARTIAL_REFUND            => 'Partially Refund',
         self::FULL_REFUND               => 'Full Refund',
         self::CUSTOMER_CANCELED         => 'Cancelled',
 
     ];

    
    /**
     * Method getOrderStatusAttribute
     *
     * @return string
    */
    public function getOrderStatusAttribute(): string
    {
        return self::ORDER_STATUS[$this->status];
    }

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
     * Get the category that owns the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * The variation that belong to the RestaurantItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(RestaurantVariation::class, 'variation_id', 'id');
    }

    /**
     * Get all of the addons for the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addons(): HasMany
    {
        return $this->hasMany(self::class, 'parent_item_id', 'id')->where('type', RestaurantItem::ADDON);
    }

    /**
     * Get all of the mixer for the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mixer(): HasOne
    {
        return $this->hasOne(self::class, 'parent_item_id', 'id')->where('type', RestaurantItem::MIXER);
    }
}
