<?php namespace App\Repositories;

use App\Models\Country;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CountryRepository.
*/
class CountryRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = Country::class;

    /**
     * Method getCountries
     *
     * @return Collection
    */
    public function getCountries() : Collection
    {
        $query      = $this->getAll();
        return $query;
    }

}