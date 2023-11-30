<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantPickupPoint extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'pickup_points';   //restaurant_pickup_points

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'restaurant_id',
        'pickup_point_id',
        'user_id',
        'type',
    ];

    const OFFLINE      = 0;
    const ONLINE       = 1;


    const STATUS = [
        self::OFFLINE     => 'OFFLINE',
        self::ONLINE      => 'ONLINE',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
     * Get the restaurant that owns the RestaurantPickupPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    /**
     * Get the user that owns the RestaurantPickupPoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Method getImageAttribute
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        return isset($this->attachment) ? asset('storage/pickup_point/'.$this->attachment->stored_name) : '';
    }

    /**
     * Method ScopeRestaurantget
     *
     * @param $query $query [explicite description]
     * @param $arg $arg [explicite description]
     *
     * @return mixed
     */
    public function ScopeRestaurantget($query,$arg)
    {
        return $query->where('restaurant_id',$arg);
    }

    /**
     * Method ScopeType
     *
     * @param $query $query [explicite description]
     * @param $arg $arg [explicite description]
     *
     * @return mixed
     */
    public function ScopeType($query,$arg)
    {
        return $query->where('type',$arg);
    }

    /**
     * Method ScopeType
     *
     * @param $query $query [explicite description]
     * @param $arg $arg [explicite description]
     *
     * @return mixed
     */
    public function ScopeStatus($query,$arg)
    {
        return $query->where('status',$arg);
    }

     /**
     * Method getStatusAttribute
     *
     * @return string
    */
    public function getPickUpStatusAttribute(): string
    {
        return self::STATUS[$this->status];
    }
}
