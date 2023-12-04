<?php

namespace App\Repositories\Traits;

use App\Exceptions\GeneralException;
use App\Models\Spin;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait SpinWheel
{
    /**
     * Method calculateWinningChance
     *
     * @param int $type [explicite description]
     * @param int $spinCount [explicite description]
     *
     * @return int
     */
    public function calculateWinningChance(User $user, $type, $spinCount): int
    {
        switch ($type) {
            case User::ONE_X:
                if ($spinCount <= 11) {

                    return $this->getOneXWinningByRange11($user, $type, [1,11]);

                } else if ( $spinCount >= 12 && $spinCount <= 22 ) {

                    return $this->getOneXWinningByRange11($user, $type, [12,22]);

                } else if ( $spinCount >= 23 && $spinCount <= 33 ) {

                    return $this->getOneXWinningByRange11($user, $type, [23,33]);

                } else if ( $spinCount >= 34 && $spinCount <= 44 ) {

                    return $this->getOneXWinningByRange11($user, $type, [34,44]);

                } else if ( $spinCount >= 45 && $spinCount <= 55 ) {

                    return $this->getOneXWinningByRange11($user, $type, [45,55]);

                } else if ( $spinCount > 55 ) {
                    // logic to get counter and range
                    $range = $this->getRangeBy17($spinCount);

                    return $this->getOneXWinningByRange17($user, $type, $range);
                }
            case User::FIVE_X:
                // return 1 / 15; // Gold users have a constant chance of 1 in 15
                // logic to get counter and range
                $range = $this->getRangeBy5($spinCount);

                return $this->getOneXWinningByRange15($user, $type, $range);
            case User::TEN_X:
                // return 1 / 13; // Platinum users have a constant chance of 1 in 13
                // Add more user types as needed
            default:
                return 0; // Default to no chance
        }
    }

    /**
     * Method getRangeBy17
     *
     * @param int $counter [explicite description]
     *
     * @return array
     */
    public function getRangeBy17($counter): array
    {
        $number     = $counter - 55;
        $roundDown  = floor($number/17);
        $newNumber  = $roundDown*17;
        $newNumber  = $newNumber+55;
        $start      = $newNumber+1;
        $end        = $newNumber+17;

        return [$start, $end];
    }

    /**
     * Method getRangeBy5
     *
     * @param int $counter [explicite description]
     *
     * @return array
     */
    public function getRangeBy5($counter): array
    {
        $number     = $counter;
        $roundDown  = floor($number/15);
        $newNumber  = $roundDown*15;
        $start      = $newNumber+1;
        $end        = $newNumber+15;

        return [$start, $end];
    }

    /**
     * Method getOneXWinningByRange11
     *
     * @param User $user [explicite description]
     * @param int $type [explicite description]
     * @param array $range [explicite description]
     *
     * @return bool
     */
    public function getOneXWinningByRange11(User $user, int $type, array $range): bool
    {
        $records = $this->getSpinCountRange($user, $type, $range);

        if( $records->count() )
        {
            $search = 1;
            $filtered = $records->filter(function($item) use ($search) {
                return stripos($item['is_winner'], $search) !== false;
            });

            if( $filtered->count() )
            {
                return false;
            }
            else
            {
                if( $records->count() == 10 )
                {
                    return true;
                }
                else
                {
                    return $this->getWinning(1,11);
                }
            }
        }

        // random
        return $this->getWinning(1,11);
    }

    /**
     * Method getOneXWinningByRange17
     *
     * @param User $user [explicite description]
     * @param int $type [explicite description]
     * @param array $range [explicite description]
     *
     * @return bool
     */
    public function getOneXWinningByRange17(User $user, int $type, array $range): bool
    {
        $records = $this->getSpinCountRange($user, $type, $range);

        if( $records->count() )
        {
            $search = 1;
            $filtered = $records->filter(function($item) use ($search) {
                return stripos($item['is_winner'], $search) !== false;
            });

            if( $filtered->count() )
            {
                return false;
            }
            else
            {
                if( $records->count() == 16 )
                {
                    return true;
                }
                else
                {
                    return $this->getWinning(1,17);
                }
            }
        }

        // random
        return $this->getWinning(1,17);
    }

    /**
     * Method getOneXWinningByRange15
     *
     * @param User $user [explicite description]
     * @param int $type [explicite description]
     * @param array $range [explicite description]
     *
     * @return bool
     */
    public function getOneXWinningByRange15(User $user, int $type, array $range): bool
    {
        $records = $this->getSpinCountRange($user, $type, $range);

        if( $records->count() )
        {
            $search = 1;
            $filtered = $records->filter(function($item) use ($search) {
                return stripos($item['is_winner'], $search) !== false;
            });

            if( $filtered->count() )
            {
                return false;
            }
            else
            {
                if( $records->count() == 14 )
                {
                    return true;
                }
                else
                {
                    return $this->getWinning(1,15);
                }
            }
        }

        // random
        return $this->getWinning(1,17);
    }

    /**
     * Method getSpinCountRange
     *
     * @param User $user [explicite description]
     * @param $type $type [explicite description]
     * @param array $range [explicite description]
     *
     * @return Collection
     */
    public function getSpinCountRange(User $user, $type, array $range): Collection
    {
        $rangeNo    = range($range[0], $range[1]);
        $count      = count($rangeNo);
        $skip       = $range[0]-1;
        // dump($range[0]);
        // dump($range[1]);
        // dump($count);
        $query      = $user->spins()->where('type', $type)->skip($skip)->take($count);

        // echo common()->formatSql($query);die;
        return $query->get();
    }

    /**
     * Method getWinning
     *
     * @param int $start [explicite description]
     * @param int $end [explicite description]
     *
     * @return bool
     */
    public function getWinning(int $start, int $end): bool
    {
        // Generate a random number between 1 and 11
        $randomNumber = rand($start, $end);

        // Check if the random number is 1 (1 in 11 chance of winning)
        return $randomNumber === 1;
    }

    /**
     * Method spinWheel
     *
     * @param User $user [explicite description]
     * @param $type $type [explicite description]
     *
     * @return bool
     */
    public function spinWheel(User $user, $type): bool
    {
        $spinCount      = $this->getSpinCounterByType($user, $type);
        $winningChance  = $this->calculateWinningChance($user, $type, $spinCount);

        // Save spin result to the database (you need to have a table for spins)
        // $user->spins()->create([
        //     'type'      => $type,
        //     'is_winner' => $winningChance
        // ]);

        return $winningChance;
    }

    /**
     * Method getSpinCounterByType [Get Total counter by spin type in nos.]
     *
     * @param User $user [explicite description]
     * @param $type $type [explicite description]
     *
     * @return int
     */
    public function getSpinCounterByType(User $user, $type): int
    {
        return $user->spins()->where('type', $type)->count();
    }

    /**
     * Method storeSpin
     *
     * @param array $data [explicite description]
     *
     * @return Spin
     * @throws \App\Exceptions\GeneralException
     */
    public function storeSpin(array $data): Spin
    {
        $user = auth()->user();

        $spin = $user->spins()->create([
            'type'      => $data['type'],
            'is_winner' => $data['is_winner']
        ]);

        if( isset($spin->id) )
        {
            $pointsToDebit = 60;
            $amountWin     = 0;

            switch($data['type'])
            {
                case User::ONE_X:
                    // update user points
                    $updatedPoints = $user->points - $pointsToDebit;

                    // update user credits if win
                    if( $data['is_winner'] == 1 )
                    {
                        $amountWin = $user->credit_amount + 2.5;
                    }
                    else
                    {
                        $amountWin = $user->credit_amount + 0;
                    }

                    break;
                case User::FIVE_X:
                    // update user points
                    $updatedPoints = $user->points - ($pointsToDebit*5);

                    // update user credits if win
                    if( $data['is_winner'] == 1 )
                    {
                        $amountWin = $user->credit_amount + 5;
                    }
                    else
                    {
                        $amountWin = $user->credit_amount + 0;
                    }
                    break;
                case User::TEN_X:
                    // update user points
                    $updatedPoints = $user->points - ($pointsToDebit*10);

                    // update user credits if win
                    if( $data['is_winner'] == 1 )
                    {
                        $amountWin = $user->credit_amount + 5;
                    }
                    else
                    {
                        $amountWin = $user->credit_amount + 0;
                    }
                    break;
                default:
                    // update user points
                    $updatedPoints = $user->points - $pointsToDebit;

                    // update user credits if win
                    if( $data['is_winner'] == 1 )
                    {
                        $amountWin = $user->credit_amount + 2.5;
                    }
                    else
                    {
                        $amountWin = $user->credit_amount + 0;
                    }

                    break;
            }

            $user->update([
                'points'        => $updatedPoints,
                'credit_amount' => $amountWin
            ]);

            return $spin;
        }

        throw new GeneralException('Spin result is failed to store.');
    }
}
