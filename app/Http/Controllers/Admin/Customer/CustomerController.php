<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /** @var \App\Repositories\UserRepository $repository*/
    protected $repository;

    /**
     * Method __construct
     *
     * @param \App\Repositories\UserRepository $repository [explicite description]
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $user_id [explicite description]
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(string $user_id)
    {
        $user = User::findOrFail($user_id);

        $this->repository->deleteUserPermanently($user);

        return response()->json([
            'success' => 'Customer deleted successfully!'
        ]);
    }
}
