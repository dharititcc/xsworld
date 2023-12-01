<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReferrals extends Model
{
    use HasFactory;

    protected $table = 'user_referrals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'points_earned_by_to_user',
        'points_earned_by_from_user'
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

    /** user types */
    const FROM_USER_POINTS    = 60;
    const TO_USER_POINTS      = 100;


    const PENDING              = 0;
    const ACCEPTED             = 1;


    const REFERRAL_STATUS = [
        self::PENDING        => 'Sent(Pending)',
        self::ACCEPTED       => 'Accepted'
    ];

    /**
     * Get the user that owns the UserPaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function touser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    /**
     * Method getReferralStatusAttribute
     *
     * @return string
    */
    public function getReferralStatusAttribute(): string
    {
        return self::REFERRAL_STATUS[$this->status];
    }
}
