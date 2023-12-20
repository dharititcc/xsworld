<?php namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FaqRepository.
*/
class FaqRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = Faq::class;

    /**
     * Method getFaq
     *
     * @return Collection
    */
    public function getFaq() : Collection
    {
        $query      = $this->getAll();
        return $query;
    }
}