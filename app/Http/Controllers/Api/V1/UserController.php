<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends APIController
{
    /** @var \App\Repositories\UserRepository $repository */
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
        $this->middleware('auth');
    }

    /**
     * Method updateProfile
     *
     * @param \App\Http\Requests\UpdateProfileRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
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
        $user       = auth()->user();
        $dataArr    = [
            'name'      => $request->get('name') ?? null,
            'phone'     => $request->get('phone') ?? null,
            'phone2'    => $request->get('phone2') ?? null,
            'birth_date'=> $request->get('birth_date') ?? null,
        ];

        if( $this->repository->update($dataArr, $user) )
        {
            return $this->respond([
                'status' => true,
                'message'=> 'Update Profile successfully.',
                'item'   => new UserResource($user)
            ]);
        }

        throw new GeneralException('Profile is failed to update.');
    }

    /**
     * Method changePassword
     *
     * @param \App\Http\Requests\ChangePasswordRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\PATCH(
     ** path="/api/v1/users/change-password",
     *   tags={"Authentication"},
     *   summary="change-password",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *
     *     @OA\Parameter(
     *      name="old_password",
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
        if(!Hash::check($request->get('old_password'), auth()->user()->password))
        {
            return $this->respondInternalError('Current password does not match.');
        }

        $user = auth()->user();
        // update password other data
        $dataArr = [
            'password'              =>Hash::make($request->new_password)
        ];

        if( $this->repository->update($dataArr, $user) )
        {
            return $this->respond([
                'status'    =>  true,
                'message'   =>  'Update Password successful',
            ]);
        }

        throw new GeneralException('Password is failed to update.');
    }

    public function resetPassword(Request $request)
    {
        $validated  = $request->validate(['email' => "required|email"]);
       // return \Response::json($arr);
    }
}
