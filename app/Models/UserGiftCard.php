<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGiftCard extends Model
{
    use HasFactory;

    protected $table = 'user_git_card';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'from_user',
        'to_user',
        'amount',
        'code',
        'status',
        'transaction_id'
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

    const PENDING       = 0;
    const REDEEMED      = 1;
}
