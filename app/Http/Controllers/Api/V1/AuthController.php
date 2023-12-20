<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Traits\Authenticate;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\SocialRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
     * @param Request $request [explicite description]
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
    public function postLogin(LoginRequest $request)
    {
        $input          = $request->all();
        $authenticated  = $this->authenticate($request);

        if( $authenticated === true )
        {
            $user = auth()->user();

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
            return $this->loginResponse($token, $user);
        }
        else if( is_array($authenticated) )
        {
            return response()->json([
                'status'        => true,
                'is_first_time' => 1
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
            'message'=> 'Mail send successfully Please check your mail.'
        ]);
    }

    /**
     * Method postRegister
     *
     * @param \App\Http\Requests\RegisterRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     ** path="/api/v1/auth/register",
     *   tags={"Authentication"},
     *   summary="Register",
     *   operationId="register",
     *
     *  @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="country",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
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
     *      name="password_confirmation",
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
     *      name="application_version",
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
     *     @OA\Parameter(
     *      name="birth_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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
    public function postRegister(RegisterRequest $request)
    {
        $dataArr = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'phone'                 => $request->phone,
            'country_code'          => $request->country_code,
            'country_id'            => $request->country,
            'address'               => $request->address,
            'registration_type'     => $request->registration_type,
            'birth_date'            => $request->birth_date,
            'platform'              => $request->platform,
            'os_version'            => $request->os_version,
            'application_version'   => $request->application_version,
            'model'                 => $request->model,
            'user_type'             => User::CUSTOMER,
            'points'                => User::SIGN_UP_POINTS,
            'social_id'             => $request->social_id,
            'referral_code'         => $request->referral_code,
            'fcm_token'             => $request->fcm_token
        ];

        $user = $this->repository->create($dataArr);
        $token  = $user->createToken('xs_world')->plainTextToken;

        if( isset($user->id) )
        {
            return $this->respond([
                'status'    => true,
                'message'   => 'Registration successfully. Now please check your email to verify your account.',
                'token'     =>  $token,
                'item'      =>  new UserResource($user),
            ]);
        }

        return $this->respondWithError('Invalid Registration data.');
    }

    /**
     * Method socialRegister
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialRegister(SocialRequest $request)
    {
        $dataArr = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name ?? '',
            'email'                 => $request->email,
            'password'              => Hash::make(Str::random(6)),
            'phone'                 => $request->phone ?? rand(),
            'country_code'          => $request->country_code,
            'country_id'            => $request->country,
            // 'address'               => $request->address,
            'registration_type'     => $request->registration_type,
            'birth_date'            => $request->birth_date,
            'platform'              => $request->platform,
            'os_version'            => $request->os_version,
            'fcm_token'             => $request->fcm_token ?? null,
            'application_version'   => $request->application_version,
            'model'                 => $request->model,
            'user_type'             => User::CUSTOMER,
            'email_verified_at'     => Carbon::now(),
            'social_id'             => $request->social_id,
            'points'                => User::SIGN_UP_POINTS,
            // 'social_id'             => $request->social_id,
            'referral_code'         => $request->referral_code
        ];

        $user = $this->repository->Socialcreate($dataArr);

        $user->refresh();

        $this->repository->storeDevice($user, ['fcm_token' => $request->fcm_token]);

        $token  = $user->createToken('xs_world')->plainTextToken;
        return $this->loginResponse($token, $user);
    }

    /**
     * Method loginResponse
     *
     * @param string $token [explicite description]
     * @param User $user [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function loginResponse(string $token, User $user): JsonResponse
    {
        return $this->respond([
            'status'    =>  true,
            'message'   =>  'Login successful',
            'token'     =>  $token,
            'item'      =>  new UserResource($user),
        ]);
    }

    /**
     * Method sendOtp
     *
     * @param SendOtpRequest $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $otp   = $this->repository->sendOtp($request->validated());

        return $this->respond([
            'status'    =>  true,
            'message'   =>  'OTP has been sent successfully',
        ]);
    }

    /**
     * Method VerifyOtp
     *
     * @param Request $request [explicite description]
     *
     * @return JsonResponse
     */
    public function VerifyOtp(Request $request) : JsonResponse
    {
        $input  = $request->all();
        $otp    = $this->repository->VerifyOtp($input);

        if( isset($otp->id) )
        {
            return $this->respond([
                'status'    =>  true,
                'message'   =>  'OTP Match',
            ]);
        }
        return $this->respondWithError('Invalid Registration data.');

    }

    /**
     * Method resendLink
     *
     * @param Request $request [explicite description]
     *
     * @return JsonResponse
     */
    public function resendLink(Request $request) : JsonResponse
    {
        $input      = $request->all();
        $resend     = $this->repository->resendLink($input);

        return $this->respond([
            'status'    =>  true,
            'message'   =>  'Verification link has been sent to the email.',
        ]);
    }
}
