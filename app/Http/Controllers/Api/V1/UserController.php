<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends APIController
{
    /** @var \App\Repositories\UserRepository $repository */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }
    /**
     * @OA\Post(
     ** path="/api/v1/users/update-profile",
     *   tags={"update-profile"},
     *   summary="update-profile",
     *     security={
     *         {"bearer_token": {}}
     *     },
     * 
     *     @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="phone2",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="birth_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
  
     *)
     **/
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->get('name') ?? null;
        $user->phone  = $request->get('phone') ?? null;
        $user->phone2 = $request->get('phone2') ?? null;
        $user->birth_date = $request->get('birth_date') ?? null;
        $user->save();
        return $this->respond([
            'status' => true,
            'message'=> 'Update Profile successfully.',
            'item'   => new UserResource($user)
        ]);
    }
    /**
     * @OA\PATCH(
     ** path="/api/v1/users/change-password",
     *   tags={"change-password"},
     *   summary="change-password",
     *     security={
     *         {"bearer_token": {}}
     *     },
     * 
     *     @OA\Parameter(
     *      name="current_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *     @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     * ),
     *    @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
  
     *)
     **/
    public function changePassword(ChangePasswordRequest $request)
    { 
        if(!Hash::check($request->get('current_password'), auth()->user()->password))
        {
            return $this->respondInternalError('Current password does not match.');
        }
        $user = auth()->user();
        // update password other data
        $dataArr = [
            'password'              =>Hash::make($request->new_password)
        ];

        $this->repository->update($dataArr, $user);
        $token  = $user->createToken('xs_world')->plainTextToken;
        return $this->respond([
            'status'    =>  true,
            'message'   =>  'Update Password successful',
            'token'     =>  $token,
            'item'      =>  new UserResource($user),
        ]);

    }
    public function resetPassword(Request $request)
    {
        $validated  = $request->validate(['email' => "required|email"]);
       // return \Response::json($arr);
    }
}
