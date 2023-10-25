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

    const PENDNIG                       = 0;
    const ACCEPTED                      = 1;
    const READY                         = 2;
    const COMPLETED                     = 3;
    const RESTAURANT_CANCELED           = 4;
    const CONFIRM_PICKUP                = 5;
    const RESTAURANT_TOXICATION         = 6;
    const PARTIAL_REFUND                = 7;
    const FULL_REFUND                   = 8;
    const DELAY_ORDER                   = 9;
    const CUSTOMER_CANCELED             = 10;
    const READYFORPICKUP                = 11;
    const KITCHEN_CONFIRM               = 12;

    const ORDER_STATUS = [
        self::PENDNIG                   => 'Pending',
        self::ACCEPTED                  => 'Accepted',
        self::READY                     => 'Ready',
        self::COMPLETED                 => 'Completed',
        self::RESTAURANT_CANCELED       => 'Cancelled',
        self::CONFIRM_PICKUP            => 'Order Pickup',
        self::RESTAURANT_TOXICATION     => 'Cancel due to Intoxication',
        self::PARTIAL_REFUND            => 'Partially Refund',
        self::FULL_REFUND               => 'Full Refund',
        self::DELAY_ORDER               => 'Delay Order',
        self::CUSTOMER_CANCELED         => 'Cancelled by customer',
        self::READYFORPICKUP            => 'Kitchen ready for pickup',
        self::KITCHEN_CONFIRM           => 'Kitchen confirm order',
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
        'cancel_date',
        'served_date',
        'completion_date',
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
        if($this->updated_at)
        {
            $updated_time   = Carbon::parse($this->updated_at);
            $apply_time     = $updated_time->addMinutes($this->apply_time);
            $remaining_time = $current_time->diffInSeconds($apply_time);
            $old_time       = Carbon::createFromFormat('Y-m-d H:i:s', $apply_time)->isPast();
            if($old_time === true)
            {
                return 0;
            }
        }
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
}
