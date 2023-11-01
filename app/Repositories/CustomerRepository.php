<?php namespace App\Repositories;

use App\Repositories\BaseRepository;

/**
 * Class CustomerRepository.
*/
class CustomerRepository extends BaseRepository
{
    public function getCustomerForDatatable()
    {
        $active = isset($input['active']) ? $input['active'] : 0;
        $query = $this->query()
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.phone'
            ]);

        if($active)
        {
            $query->onlyTrashed();
        }

        return $query;
    }
}