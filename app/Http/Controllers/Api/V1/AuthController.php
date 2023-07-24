<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Traits\Authenticate;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Method postLogin
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request)
    {
        $input = $request->all();
        if( $this->authenticate($request) )
        {
            $user = auth()->user();

            // update all other data
            $dataArr = [
                'name'                  => $input['name'],
                'platform'              => $input['platform'],
                'os_version'            => $input['os_version'],
                'application_version'   => $input['application_version'],
                'model'                 => $input['model'],
            ];

            $user->update($dataArr);

            $token  = $user->createToken('xs_world')->plainTextToken;
            return $this->respond([
                'status'    =>  true,
                'message'   =>  'Login successful',
                'token'     =>  $token,
                'item'      =>  new UserResource($user),
            ]);
        }

        return $this->respondInternalError('Invalid login credentials.');
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
    public function logout(Request $request)
    {
        try
        {
            // Get user who requested the logout
            $user = auth()->user(); //or Auth::user()
            // Revoke current user token
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        }
        catch (\Exception $e)
        {
            return $this->respondInternalError($e->getMessage());
        }

        return $this->respondSuccess('Logged out successfully.');
    }
    /**
     * Method postRegister
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request)
    {
        if( isset( $request->registration_type ) )
        {
            $type = intval($request->registration_type);
            switch( $type )
            {
                case User::GOOGLE:
                    // validation
                    $this->validateEmail($request);

                    $user = User::find($request->email);

                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    break;
                case User::FACEBOOK:
                    // validation
                    $this->validateEmail($request);

                    $user = User::find($request->email);

                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    break;
                default:
                    // validation
                   
                    $this->validateRegister($request);
                    $user = User::create($request->all());
                    return $this->respond([
                        'status'    =>  true,
                        'message'   =>  'Registeration successful',
                        'item'      =>  new UserResource($user),
                    ]);
                    break;
            }
        }
        return $this->respondInternalError('Invalid Registration data.');
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRegister(Request $request)
    {
       // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|string',
            'phone' => 'required|unique:users,phone',
            'registration_type' => 'required',
            'birth_date' => 'required',
            'platform' => 'required',
            'os_version' => 'required',
            'application_version' => 'required',
            'model' => 'required'
        ]);
    }
}
