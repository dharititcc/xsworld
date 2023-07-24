<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Traits\Authenticate;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

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

            $user->update($input);

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
}
