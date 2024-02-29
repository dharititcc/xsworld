<?php namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class CustomerRepository.
*/
class CustomerRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = User::class;

    public function getCustomerForDatatable(array $data)
    {
        $active = isset($input['active']) ? $input['active'] : 0;
        $search = $data['search_main'];
        $query = $this->query()
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.phone'
            ])->where('user_type', User::CUSTOMER);

        if($search)
        {
            $query =  $query->where(function($query) use($search)
            {
                $query->where('users.first_name' , 'LIKE', '%'.$search.'%')
                            ->orWhere('users.last_name', 'LIKE', '%'.$search.'%')
                            ->orWhere('users.email', 'LIKE', '%'.$search.'%')
                            ->orWhere('users.phone', 'LIKE', '%'.$search.'%');
            });
        }

        return $query;
    }
}