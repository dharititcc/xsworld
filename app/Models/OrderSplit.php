<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderSplit extends Model
{
    use HasFactory;

    /** @var string $table */
    protected $table = 'order_splits';

    const PENDING = 0;

    const STATUS = [
        self::PENDING => 'Pending'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'status',
        'category_id'
    ];

    /**
     * Get the order that owns the OrderSplit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        return self::STATUS[$this->status];
    }
}