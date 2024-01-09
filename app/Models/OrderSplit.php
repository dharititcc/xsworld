<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderSplit extends Model
{
    use HasFactory;

    /** @var string $table */
    protected $table = 'order_splits';

    /** BAR STATUSES START */
    const PENDING               = 0;
    const ACCEPTED              = 1;
    const COMPLETED             = 3;
    const CONFIRM_PICKUP        = 5;
    const RESTAURANT_TOXICATION = 6;
    const DELAY_ORDER           = 9;
    const DENY_ORDER            = 13;
    /** BAR STATUS END */

    /** KITCHEN STATUS START */
    const CURRENTLY_BEING_PREPARED  = 16;
    const READYFORPICKUP            = 11;
    const KITCHEN_CONFIRM           = 12;
    const KITCHEN_CANCELED          = 18;
    /** KITCHEN STATUS END */

    const STATUS = [
        self::PENDING                   => 'Pending',       // display in bar, kitchen, waiter
        self::ACCEPTED                  => 'Accepted',
        self::COMPLETED                 => 'Completed', // Bar ready
        self::CONFIRM_PICKUP            => 'Confirm Pickup', // customer or waiter collected
        self::RESTAURANT_TOXICATION     => 'Cancelled due to intoxication', // restaurant cancelled order
        self::DELAY_ORDER               => 'Delay',
        self::DENY_ORDER                => 'Deny Order', // restaurant cancelled the order due to some reason (e.g. stock or item not available)
        self::READYFORPICKUP            => 'Ready For Pickup', // Kitchen prepared food. Waiter need to notify for pickup food from kitchen
        self::KITCHEN_CONFIRM           => 'Confirmed Pickup', // Kitchen collection orders. Need waiter to pick the order
        self::CURRENTLY_BEING_PREPARED  => 'Currently Being Prepared', // waiter placed order of food by defualt
        self::KITCHEN_CANCELED          => 'Kitchen Canceled', //Kitchen Canceled food. Waiter/Customer need to notify
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'status',
        'is_food'
    ];

    /**
     * Get the order that owns the OrderSplit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Method getStatusNameAttribute
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return self::STATUS[$this->status];
    }

    /**
     * Get all of the items for the OrderSplit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_split_id', 'id')->where('type', RestaurantItem::ITEM);
    }
}