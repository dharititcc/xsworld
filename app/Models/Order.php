<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /** order types */
    const CART  = 1;
    const ORDER = 2;

    const PENDNIG           = 0;
    const ACCEPTED          = 1;
    const READY             = 2;
    const COMPLETED         = 3;
    const CANCELED          = 4;
    const PARTIAL_REFUND    = 5;
    const FULL_REFUND       = 6;

    const ORDER_STATUS = [
        self::PENDNIG           => 'Pending',
        self::ACCEPTED          => 'Accepted',
        self::READY             => 'Ready',
        self::COMPLETED         => 'Completed',
        self::CANCELED          => 'Canceled',
        self::PARTIAL_REFUND    => 'Partially Refund',
        self::FULL_REFUND       => 'Full Refund',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'restaurant_pickup_point_id',
        'pickup_point_id',
        'pickup_point_user_id',
        'type',
        'status',
        'card_id',
        'charge_id',
        'payment_method_id',
        'credit_point',
        'amount',
        'transaction_id',
        'currency_id',
        'apply_time',
        'accepted_date',
        'total',
        'cancel_reason'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'accepted_date' => 'datetime'
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
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the restaurant that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get the pickup_point that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pickup_point(): BelongsTo
    {
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id', 'id');
    }

    /**
     * Get the pickup_point_user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pickup_point_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pickup_point_user_id', 'id');
    }

    /**
     * Get the payment_method that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(UserPaymentMethod::class, 'user_id ', 'id');
    }

    /**
     * Get all of the items for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    /**
     * Method order_items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->where('type', RestaurantItem::ITEM);
    }

    /**
     * Method order_addons
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_addons(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->where('type', RestaurantItem::ADDON);
    }

    /**
     * Method order_mixer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_mixer(): HasOne
    {
        return $this->hasOne(OrderItem::class, 'order_id', 'id')->where('type', RestaurantItem::MIXER);
    }

    /**
     * Method scopeOrder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrder(Builder $query): Builder
    {
        return $query->where('type', self::ORDER);
    }

    /**
     * Method scopeCart
     *
     * @param \Illuminate\Database\Eloquent\Builder $query [explicite description]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCart(Builder $query): Builder
    {
        return $query->where('type', self::CART);
    }

    /**
     * Method getProgressattribute
     *
     * @return string
     */
    public function getProgressattribute()
    {
        $remaining_time     = 0;
        $current_time       = Carbon::now()->timezone('Asia/kolkata');

        if(isset($this->accepted_date))
        {
            $accepted_time  = Carbon::createFromFormat('Y-m-d H:i:s', $this->accepted_date, 'Asia/Kolkata');
            // dd($accepted_time);
            $apply_time     = $accepted_time->addMinutes($this->apply_time);
            $remaining_time = $apply_time->diffInSeconds($current_time);
            // dd($current_time);
        }

        return $remaining_time;
    }
}
