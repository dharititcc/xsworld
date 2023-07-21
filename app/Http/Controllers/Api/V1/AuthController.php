<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Traits\Authenticate;
use App\Http\Resources\UserResource;
use App\Models\User;
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
     * @return void
     */
    public function postLogin(Request $request)
    {
        $user = $this->authenticate($request);

        if( $user === true )
        {
            $user = User::where('email', $request->email)->first();
        }

        if( $user instanceof \App\Models\User && isset( $user->id ) )
        {
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
