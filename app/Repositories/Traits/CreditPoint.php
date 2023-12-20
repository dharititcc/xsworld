<?php

namespace App\Repositories\Traits;

use App\Exceptions\GeneralException;
use App\Models\CreditPointsHistory;
use App\Models\User;

trait CreditPoint
{
    /**
     * Method insertCreditPoints
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     *
     * @return \App\Models\CreditPointsHistory
     */
    public function insertCreditPoints(User $user, array $data): CreditPointsHistory
    {
        return $user->credit_points()->create($data);
    }

    /**
     * Method updateUserPoints
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     *
     * @return bool
     */
    public function updateUserPoints(User $user, array $data): bool
    {
        return $user->update($data);
    }
}