<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Impersonate;

    protected $table = 'users';

    /** user types */
    const CUSTOMER          = 1;
    const RESTAURANT_OWNER  = 2;
    const ADMIN             = 3;
    const BARTENDER         = 4;
    const WAITER            = 5;
    const KITCHEN           = 6;

    /** Registration types */
    const EMAIL     = 0;
    const PHONE     = 1;
    const GOOGLE    = 2;
    const FACEBOOK  = 3;
    const USERNAME  = 4;
    const APPLE     = 5;

    const SIGN_UP_POINTS    = 30;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'phone2',
        'registration_type',
        'user_type',
        'fcm_token',
        'country_id',
        'cus_qr_code_img',
        'stripe_customer_id',
        'country_code',
        'birth_date',
        'address',
        'platform',
        'os_version',
        'application_version',
        'model',
        'credit_amount',
        'points',
        'username',
        'verification_code',
        'is_mobile_verify',
        'referral_code',
        'referrer_id',
        'set_id',
        'email_verified_at',
        'social_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone'             => 'integer',
    ];

    /**
     * Get all of the spins for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spins(): HasMany
    {
        return $this->hasMany(Spin::class, 'user_id', 'id');
    }

    /**
     * Get all of the devices for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(UserDevices::class, 'user_id', 'id');
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

    public function restaurant_kitchen()
    {
        return $this->hasOne(RestaurantKitchen::class,'user_id','id');
    }


    public function restaurant_waiter()
    {
        return $this->hasOne(RestaurantWaiter::class,'user_id','id')->withTrashed();
    }

    public function kitchen_pickup_point()
    {
        return $this->hasMany(KitchenPickPoint::class,'user_id');
    }

    /**
     * The restaurants that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_owners', 'user_id', 'restaurant_id');
    }

    /**
     * Get all of the payment_methods for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_methods(): HasMany
    {
        return $this->hasMany(UserPaymentMethod::class, 'user_id', 'id');
    }

    /**
     * The favourite_items that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favourite_items(): BelongsToMany
    {
        return $this->belongsToMany(RestaurantItem::class, 'user_favourite_items', 'user_id', 'restaurant_item_id')->withPivot(['created_at', 'updated_at']);
    }

    /**
     * Get the country that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Method getImageAttribute
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        return isset($this->attachment) ? asset('storage/profile/'.$this->attachment->stored_name) : '';
    }

     /**
     * Method getQrImageAttribute
     *
     * @return string
     */
    public function getQrImageAttribute(): string
    {
        return isset($this->cus_qr_code_img) ? asset('customer_qr/'.$this->cus_qr_code_img) : '';
    }

    /**
     * Get the pickup_point associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pickup_point(): HasOne
    {
        return $this->hasOne(RestaurantPickupPoint::class, 'user_id', 'id');
    }

    /**
     * Get all of the orders for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')->where('type', Order::ORDER);
    }

    /**
     * Get all of the waiter_order for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waiter_order(): HasMany
    {
        return $this->hasMany(Order::class, 'waiter_id', 'id')->where('type', Order::ORDER);
    }

    /**
     * Get all of the carts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')->where('type', Order::CART);
    }

    /**
     * Get all of the latest_cart for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latest_cart(): HasOne
    {
        return $this->hasOne(Order::class, 'user_id', 'id')->where('type', Order::CART)->latest();
    }

    /**
     * Get all of the latest_order for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latest_order(): HasOne
    {
        return $this->hasOne(Order::class, 'user_id', 'id')->where('type', Order::ORDER)->latest();
    }

    /**
     * Method getNameAttribute
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        $name = '';

        if( $this->first_name )
        {
            $name = $this->first_name;
        }

        if( $this->last_name )
        {
            $name .= " ".$this->last_name;
        }

        return $name;
    }

    /**
     * Get the kitchen associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kitchen(): HasOne
    {
        return $this->hasOne(RestaurantKitchen::class, 'user_id', 'id');
    }

    /**
     * Method getReferralLinkAttribute
     *
     * @return string
     */
    public function getReferralLinkAttribute()
    {
        $url = 'https://xsworld.page.link/?link=https://express.itcc.net.au/?referral='.$this->referral_code.'&ibi=com.xsworld.iOS&isi=1074964262&efr=1&apn=com.itcc.xsworld&efr=1';

        //IOS : https://xsworld.page.link/?link=https://express.itcc.net.au/?referral=Manthan&ibi=com.xsworld.iOS&isi=1074964262&efr=1&apn=com.itcc.xsworld&efr=1

        // Android : https://xsworld.page.link/?link=https://express.itcc.net.au/?referral=Manthan&apn=com.itcc.xsworld&efr=1
        return $url;
    }

    /**
     * Get all of the referrals for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'referrer_id', 'id');
    }

    /**
     * Get all of the credit_points for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function credit_points(): HasMany
    {
        return $this->hasMany(CreditPointsHistory::class, 'user_id', 'id');
    }
}
