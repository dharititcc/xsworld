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
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    /** user types */
    const CUSTOMER          = 1;
    const RESTAURANT_OWNER  = 2;
    const ADMIN             = 3;
    const BARTENDER         = 4;

    /** Registration types */
    const EMAIL     = 0;
    const PHONE     = 1;
    const GOOGLE    = 2;
    const FACEBOOK  = 3;
    const USERNAME  = 4;

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
        'stripe_customer_id',
        'country_code',
        'birth_date',
        'address',
        'platform',
        'os_version',
        'application_version',
        'model',
        'credit_points'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Method attachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
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
     * Get the pickup_point associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pickup_point(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
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
}
