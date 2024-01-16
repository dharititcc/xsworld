<?php

namespace App\Models;

use App\Billing\Stripe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'orders';

    /** order types */
    const CART  = 1;
    const ORDER = 2;

    /** WAITER NOTIFICATION CODES */
    const WAITER_NEW_ORDER              = 1;
    const WAITER_CANCEL_ORDER           = 2;
    const WAITER_READY_FOR_COLLECTION   = 3;
    const WAITER_CONFIRM_COLLECTION     = 4;

    // Customer status
    const PENDNIG                       = 0;
    // const BAR_PENDING                   = 0;
    const CUSTOMER_CANCELED             = 10;

    // Bartender status
    const ACCEPTED                      = 1;
    // const BAR_CONFIRM                   = 1;
    const READY                         = 2;
    // const BAR_READY                     = 2;
    const COMPLETED                     = 3;
    // const BAR_COMPLETED                 = 3;
    const DELAY_ORDER                   = 9;
    const CONFIRM_PICKUP                = 5;
    const RESTAURANT_TOXICATION         = 6;

    // Kitchen status
    const READYFORPICKUP                = 11;
    const KITCHEN_CONFIRM               = 12;
    const KITCHEN_CANCELED              = 18;

    //waiter status
    const WAITER_PENDING                = 15;
    const IDLE                          = 14;

    
    // Admin status
    const PARTIAL_REFUND                = 7;
    const FULL_REFUND                   = 8;

    const RESTAURANT_CANCELED           = 4;

    const DENY_ORDER                    = 13;

    /** WAITER STATUS START */
    const CURRENTLY_BEING_PREPARED  = 16;
    const CURRENTLY_BEING_SERVED    = 17;
    const AWAITING_SERVICE          = 19;
    const READY_FOR_COLLECTION      = 11;
    /** WAITER STATUS END */

    /** ORDER CATEGORY TYPES */
    const DRINK= 0;
    const FOOD = 1;
    const BOTH = 2;

    const ORDER_STATUS = [
        self::PENDNIG                   => 'Pending',
        self::ACCEPTED                  => 'Accepted',
        self::READY                     => 'Ready',
        self::COMPLETED                 => 'Completed',
        self::RESTAURANT_CANCELED       => 'Restaurant Cancelled',
        self::CONFIRM_PICKUP            => 'Order Pickup',
        self::RESTAURANT_TOXICATION     => 'Restaurant Toxication',
        self::PARTIAL_REFUND            => 'Partially Refund',
        self::FULL_REFUND               => 'Full Refund',
        self::DELAY_ORDER               => 'Delay Order',
        self::CUSTOMER_CANCELED         => 'Cancelled',
        self::READYFORPICKUP            => 'ready for Collection',
        self::KITCHEN_CONFIRM           => 'Kitchen confirm order',
        self::WAITER_PENDING            => 'Order taking',
        self::DENY_ORDER                => 'Deny Order',
        // self::BAR_PENDING               => 'Bar pending',
        // self::BAR_CONFIRM               => 'Bar accepted/Confirm order',
        // self::BAR_READY                 => 'Bar ready',
        // self::BAR_COMPLETED             => 'Bar completed',
        self::CURRENTLY_BEING_PREPARED  => 'Currently Being Prepared',
        self::CURRENTLY_BEING_SERVED    => 'Currently Being Served',
        self::AWAITING_SERVICE          => 'Awaiting Service',
        self::READY_FOR_COLLECTION      => 'Ready for collection',
        self::KITCHEN_CANCELED          => 'Kitchen Cancelled',
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
        'restaurant_table_id',
        'pickup_point_user_id',
        'waiter_id',
        'type',
        'status',
        'waiter_status',
        'order_category_type',
        'card_id',
        'charge_id',
        'payment_method_id',
        'credit_amount',
        'amount',
        'transaction_id',
        'currency_id',
        'apply_time',
        'last_delayed_time',
        'accepted_date',
        'remaining_date',
        'cancel_date',
        'served_date',
        'completion_date',
        'place_at',
        'total',
        'cancel_reason',
        'refunded_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'accepted_date'     => 'datetime',
        'completion_date'   => 'datetime'
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
     * Method getWaiterStatusAttribute
     *
     * @return string
    */
    public function getWaiterStatusNameAttribute(): string
    {
        return self::ORDER_STATUS[$this->waiter_status];
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
     * Get the customer_table associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer_table(): HasOne
    {
        return $this->hasOne(CustomerTable::class, 'order_id', 'id');
    }

    /**
     * Get the restaurant_waiter that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_waiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'waiter_id', 'id');
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
     * Get the restaurant_pickup_point that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_pickup_point(): BelongsTo
    {
        return $this->belongsTo(RestaurantPickupPoint::class, 'pickup_point_id', 'id');
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
     * Get all of the reviews for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(OrderReview::class, 'order_id', 'id');
    }

    /**
     * Get the review associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(OrderReview::class, 'order_id', 'id');
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

    /**
     * Method getRemainingTimeattribute
     *
     * @return string
     */
    public function getRemainingTimeattribute()
    {
        $remaining_time     = 0;
        $current_time       = Carbon::now();
        $remaining_time     = $current_time->diffInSeconds($this->remaining_date);
        $old_time           = Carbon::parse($this->remaining_date)->isPast();
        if($old_time == true)
        {
            return 0;
        }
        // $current_time       = Carbon::now();
        // if($this->accepted_date)
        // {
        //     $updated_time   = Carbon::parse($this->accepted_date);
        //     $apply_time     = $updated_time->addMinutes($this->apply_time);
        //     $remaining_time = $current_time->diffInSeconds($apply_time);
        //     $old_time       = Carbon::createFromFormat('Y-m-d H:i:s', $apply_time)->isPast();
        //     if($old_time === true)
        //     {
        //         return 0;
        //     }
        // }
        return $remaining_time;
    }

    /**
     * Method getCardDetailsattribute
     *
     * @return array
     */
    public function getCardDetailsattribute()
    {
        $card_details = new stdClass;
        if($this->card_id)
        {
            $stripe = new Stripe();
            $card   = $stripe->retrieveCharge($this->charge_id);

            if(isset($card->source->id))
            {
                $card_details = [
                    'name'  =>  $card->source->name,
                    'brand' =>  $card->source->brand,
                    'last4' =>  $card->source->last4
                ];
                return $card_details;
            }

        }
        else
        {
            return $card_details;
        }
    }

    /**
     * Method getCompletionTimeattribute
     *
     * @return string
     */
    function getCompletionTimeattribute()
    {
        $completion_time = '';
        if($this->completion_date)
        {
            $completion_time   = Carbon::createFromFormat('Y-m-d H:i:s',$this->completion_date)->format('H:i');
        }
        return $completion_time;
    }

    /**
     * Method getServedTimeattribute
     *
     * @return string
     */
    function getServedTimeattribute()
    {
        $served_time = '';
        if($this->served_date)
        {
            $served_time   = Carbon::createFromFormat('Y-m-d H:i:s',$this->served_date)->format('h:i A');
        }
        return $served_time;
    }

    /**
     * Get all of the order_splits for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_splits(): HasMany
    {
        return $this->hasMany(OrderSplit::class, 'order_id', 'id');
    }

    /**
     * Get the order_split_drink associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_split_drink(): HasOne
    {
        return $this->hasOne(OrderSplit::class, 'order_id', 'id')->where('is_food', 0);
    }

    /**
     * Get the order_split_food associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_split_food(): HasOne
    {
        return $this->hasOne(OrderSplit::class, 'order_id', 'id')->where('is_food', 1);
    }

    /**
     * Method getPdfUrlAttribute
     *
     * @return string
     */
    public function getPdfUrlAttribute(): string
    {
        return isset($this->id) ? asset('storage/order_pdf/invoice_'.$this->id.'.pdf') : '';
    }

}
