<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerTableController extends Controller
{
    /** @var \App\Repositories\CustomerRepository $repository */
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\CustomerRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $input = $request->all();
        return DataTables::of($this->repository->getCustomerForDatatable($input))
            ->escapeColumns(['id'])
            ->editColumn('image', function(User $user)
            {
                return "<img src='{$user->image}' width='30' />";
            })
            ->addColumn('actions', function(User $user)
            {
                return $user->action_buttons;
            })
            ->make(true);
    }
}
