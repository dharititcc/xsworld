<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenPickPoint extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant_pickup_point()
    {
        return $this->belongsTo(RestaurantPickupPoint::class,'pickup_point_id','id');
    }
}
