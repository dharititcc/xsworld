<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spin extends Model
{
    use HasFactory;

    protected $table = 'spins';

    const ONE_X     = 0;
    const FIVE_X    = 1;
    const TEN_X     = 2;


    const SPIN_STATUS = [
        self::ONE_X     => '1X',
        self::FIVE_X    => '5X',
        self::TEN_X     => '10X',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'is_winner',
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the Spin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Method getSpinTypeAttribute
     *
     * @return string
    */
    public function getSpinTypeAttribute(): string
    {
        return self::SPIN_STATUS[$this->status];
    }
}
