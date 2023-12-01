<?php

namespace App\Http\Controllers\Api\V1\Waiter;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Traits\Authenticate;
use App\Http\Requests\BartenderLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\BartenderUserResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WaiterResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * @group Authentication
 *
 * Class AuthController
 *
 * Fullfills all aspects related to authenticate a user.
 */
class AuthController extends APIController
{
    use Authenticate;

    /** @var \App\Repositories\UserRepository $repository */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method postLogin
     *
     * @param LoginRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     ** path="/api/v1/auth/login",
     *   tags={"Authentication"},
     *   summary="Login",
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *      description="Email field is required when user tried to login with registration_type = 0/2/3"
     *   ),
     *   @OA\Parameter(
     *       name="phone",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      ),
     *      description="Phone field is required when user tried to login with registration_type = 1"
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="fcm_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="platform",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="os_version",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="application_version",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="model",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="registration_type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Response(
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
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function postLogin(BartenderLoginRequest $request)
    {
        $input = $request->all();

        if( $this->authenticate($request) )
        {
            $user = auth()->user();

            if( !access()->isWaiter() )
            {
                return $this->respondWithError('Invalid login credentials. Please use Waiter credentials.');
            }

            // update all other data
            $dataArr = [
                'platform'              => $input['platform'],
                'os_version'            => $input['os_version'],
                'application_version'   => $input['application_version'],
                'model'                 => $input['model'],
                'fcm_token'             => $input['fcm_token'],
            ];

            $this->repository->storeDevice($user, ['fcm_token' => $input['fcm_token']]);

            $this->repository->update($dataArr, $user);

            $token  = $user->createToken('xs_world')->plainTextToken;
            return $this->respond([
                'status'    =>  true,
                'message'   =>  'Login successful',
                'token'     =>  $token,
                'item'      =>  new WaiterResource($user),
            ]);
        }

        return $this->respondWithError('Invalid login credentials.');
    }

    /**
     * Attempt to logout the user.
     *
     * After successfull logut the token get invalidated and can not be used further.
     *
     * @responseFile status=401 scenario="api_key not provided" responses/unauthenticated.json
     * @responseFile responses/auth/logout.json
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     ** path="/api/v1/auth/logout",
     *   tags={"Authentication"},
     *   summary="logout",
     *
     *   @OA\Response(
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
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function logout(Request $request)
    {
        try
        {
            // Get user who requested the logout
            $user = auth()->user(); //or Auth::user()

            // revoke device token
            if( isset( $request->fcm_token ) )
            {
                $user->devices()->where('fcm_token', $request->fcm_token)->delete();
            }

            // Revoke current user token
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        }
        catch (\Exception $e)
        {
            return $this->respondWithError($e->getMessage());
        }

        return $this->respondSuccess('Logged out successfully.');
    }

    /**
     * Method resetPassword
     *
     * @param \App\Http\Requests $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\GeneralException
     */
    /**
     * @OA\PATCH(
     ** path="/api/v1/auth/password/reset",
     *   tags={"Authentication"},
     *   summary="password/reset",
     *
     *     @OA\Parameter(
     *      name="email",
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
    public function resetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
                return $this->respond([
                    'status' => true,
                    'message'=> 'Mail send successfully Please check your mail.',
                    'item'   => $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)])
                ]);
    }
}
