<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenPickPoint extends Model
{
    use HasFactory;

    protected $table = 'kitchen_pick_points';

    protected $guarded = ["id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pickup_point_id'
    ];

    /**
     * Method user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Method restaurant_pickup_point
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant_pickup_point()
    {
        return $this->belongsTo(RestaurantPickupPoint::class,'pickup_point_id','id');
    }
}
