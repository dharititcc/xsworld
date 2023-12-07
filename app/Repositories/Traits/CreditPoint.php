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
     * @return bool
     * @throws \App\Exceptions\GeneralException
     */
    public function insertCreditPoints(User $user, array $data): bool
    {
        $creditPoint = CreditPointsHistory::create($data);

        if( isset( $creditPoint->id ) )
        {
            return $this->updateUserPoints($user, [
                'points' => $data['points'],
                'credit_amount' => $data['amount']
            ]);
        }

        throw new GeneralException('Failed to create credit point history.');
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