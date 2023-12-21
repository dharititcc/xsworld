<?php

namespace App\Repositories\Traits;

use App\Models\Set;
use App\Models\Spin;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait SpinWheel
{
    use CreditPoint;

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
            case Spin::ONE_X:
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
            case Spin::FIVE_X:
                // logic to get counter and range
                $range = $this->getRangeBy3($spinCount);

                return $this->getOneXWinningByRange15($user, $type, $range);
            case Spin::TEN_X:

                // // logic to get counter and range
                // $range = $this->getRangeBy4($spinCount);

                // $records        = $this->getSpinCountRange($user, $type, $range);

                // if( $records->count() == 0 )
                // {
                //     // get random set for user and update
                //     $set = $this->getRandomSet();

                //     // update user set
                //     $user = $this->updateUserSet($user, $set);
                // }
                // else
                // {
                //     $set = $this->getSet($user->set_id);
                // }

                // return $this->getOneXWinningByRange13($user, $type, $range, $set, $records);

                if( $spinCount > 0 )
                {
                    // get last winning record of the 10x spin type
                    $lastRecord = $user->spins()->where('type', Spin::TEN_X)->orderBy('id', 'desc')->first();

                    // check if $spinCount % 3 == 0
                    if( $spinCount % 13 == 0 )
                    {
                        return $lastRecord->is_winner;
                    }
                    else
                    {
                        return !$lastRecord->is_winner;
                    }
                }
                else
                {
                    return $this->getWinning(1,13);
                }
            default:
                return 0; // Default to no chance
        }
    }

    /**
     * Method getSet
     *
     * @param int $set [explicite description]
     *
     * @return Set
     */
    // public function getSet(int $set): Set
    // {
    //     return Set::findOrFail($set);
    // }

    /**
     * Method getRandomSet
     *
     * @return \App\Models\Set
     */
    // public function getRandomSet(): Set
    // {
    //     return Set::select('id', 'scenario')->inRandomOrder()->first();
    // }

    /**
     * Method updateUserSet
     *
     * @param User $user [explicite description]
     * @param Set $set [explicite description]
     *
     * @return User
     */
    // public function updateUserSet(User $user, Set $set): User
    // {
    //     $user->update(['set_id' => $set->id]);

    //     return $user->refresh();
    // }

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
     * Method getRangeBy3
     *
     * @param int $counter [explicite description]
     *
     * @return array
     */
    public function getRangeBy3($counter): array
    {
        $number     = $counter;
        $roundDown  = floor($number/3);
        $newNumber  = $roundDown*3;
        $start      = $newNumber+1;
        $end        = $newNumber+3;

        return [$start, $end];
    }

    /**
     * Method getRangeBy4
     *
     * @param int $counter [explicite description]
     *
     * @return array
     */
    // public function getRangeBy4($counter): array
    // {
    //     $number     = $counter;
    //     $roundDown  = floor($number/4);
    //     $newNumber  = $roundDown*4;
    //     $start      = $newNumber+1;
    //     $end        = $newNumber+4;

    //     return [$start, $end];
    // }

    /**
     * Method getRangeBy13
     *
     * @param int $counter [explicite description]
     *
     * @return array
     */
    public function getRangeBy13($counter): array
    {
        $number     = $counter;
        $roundDown  = floor($number/13);
        $newNumber  = $roundDown*13;
        $start      = $newNumber+1;
        $end        = $newNumber+13;

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
                if( $records->count() == 2 )
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
        return $this->getWinning(1,15);
    }

    /**
     * Method getOneXWinningByRange13
     *
     * @param User $user [explicite description]
     * @param int $type [explicite description]
     * @param array $range [explicite description]
     * @param Set $set [explicite description]
     * @param Collection $records [explicite description]
     *
     * @return bool
     */
    public function getOneXWinningByRange13(User $user, int $type, array $range, Set $set, Collection $records): bool
    {
        $scenarios      = $set->scenario;
        $scenariosArr   = explode(',', $scenarios);
        $recordsCounter = $records->count();
        $result         = 0;

        if( $recordsCounter )
        {
            $result = $scenariosArr[$recordsCounter];
        }
        else
        {
            $result = $scenariosArr[$recordsCounter];
        }

        return (bool) $result;
    }

    /**
     * Method getTenXWinningByRange13
     *
     * @param User $user [explicite description]
     * @param int $type [explicite description]
     * @param array $range [explicite description]
     *
     * @return bool
     */
    public function getTenXWinningByRange13(User $user, int $type, array $range): bool
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
                    return $this->getWinning(1,13);
                }
            }
        }

        // random
        return $this->getWinning(1,13);
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
     * @return bool
     */
    public function storeSpin(array $data): bool
    {
        $user = auth()->user();

        $pointsToDebit = 60;
        $amountWin     = 0;
        $updatedPoints = 0;
        $spin           = new Spin();

        switch($data['type'])
        {
            case Spin::ONE_X:
                // insert spin record
                $spin = $this->insertSpinResult($user, ['type' => Spin::ONE_X, 'is_winner' => $data['is_winner']]);

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
            case Spin::FIVE_X:
                // update user points
                $pointsToDebit = $pointsToDebit*5;
                $updatedPoints = $user->points - ($pointsToDebit);

                // update user credits if win
                if( $data['is_winner'] == 1 )
                {
                    $amountWin = $user->credit_amount + 5;
                }
                else
                {
                    $amountWin = $user->credit_amount + 0;
                }
                // insert spin record
                $spin = $this->insertSpinResult($user, ['type' => Spin::FIVE_X, 'is_winner' => $data['is_winner']]);
                break;
            case Spin::TEN_X:
                // update user points
                $pointsToDebit = $pointsToDebit*10;
                $updatedPoints = $user->points - ($pointsToDebit);

                // update user credits if win
                if( $data['is_winner'] == 1 )
                {
                    // insert spin record
                    $spin = $this->insertSpinResult($user, ['type' => Spin::TEN_X, 'is_winner' => $data['is_winner']]);
                    $amountWin = $user->credit_amount + 5;
                }
                else
                {
                    // insert spin record
                    $spin = $this->insertSpinResult($user, ['type' => Spin::TEN_X, 'is_winner' => $data['is_winner']]);
                    $amountWin = $user->credit_amount + 0;
                }
                break;
            default:
                // insert spin record
                $spin = $this->insertSpinResult($user, ['type' => Spin::ONE_X, 'is_winner' => $data['is_winner']]);

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

        // insert credit point histories table
        $arrCreditPoint = [
            'model_name' => '\App\Models\Spin',
            'model_id'   => $spin->id,
            'points'     => $pointsToDebit * -1,
            'type'       => 0
        ];

        $userArr = [
            'points'        => $updatedPoints,
            'credit_amount' => $amountWin
        ];

        $this->insertCreditPoints($user, $arrCreditPoint);

        $this->updateUserPoints($user, $userArr);

        return true;
    }

    /**
     * Method insertSpinResult
     *
     * @param User $user [explicite description]
     * @param array $data [explicite description]
     *
     * @return Spin
     */
    public function insertSpinResult(User $user, array $data): Spin
    {
        return $user->spins()->create($data);
    }

    /**
     * Method myWinning
     *
     * @return Collection
     */
    public function myWinning():Collection
    {
        $user = auth()->user();

        return $user->spins()->where('is_winner', 1)->get();
    }
}
