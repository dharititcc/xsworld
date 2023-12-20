<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerTable extends Model
{
    use HasFactory, SoftDeletes;
    // protected $guarded = [];

    protected $fillable = [
        'order_id',
        'restaurant_table_id',
        'waiter_id',
        'user_id'
    ];

    const AWAITING_SERVICE                = 19;

    const ORDER_STATUS = [
        self::AWAITING_SERVICE                   => 'Awaiting Service',
    ];

    /**
     * Method getOrderStatusAttribute
     *
     * @return string
    */
    public function getOrderStatusAttribute(): string
    {
        return self::ORDER_STATUS[self::AWAITING_SERVICE];
    }

    /**
     * Get the table_order that owns the CustomerTable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table_order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    /**
     * Get the restaurant_table that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id', 'id')->withTrashed();
    }

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
