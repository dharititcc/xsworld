<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditPointsHistory extends Model
{
    use HasFactory;
    protected $table = 'credit_points_histories';

    protected $fillable = [
        'user_id',
        'order_id',
        'credit_point',
        'debit_points',
        'total'
    ];

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
     * Get the order that owns the OrderReview
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
