<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

trait Authenticate
{
    use AuthenticatesUsers;

    /**
     * Method authenticate
     *
     * @param Request $request [explicite description]
     *
     * @return bool
     */
    public function authenticate(Request $request): bool
    {
        return $this->getLoginType($request);
    }

    /**
     * Method getLoginType
     *
     * @param Request $request [explicite description]
     *
     * @return bool
     */
    public function getLoginType(Request $request)
    {
        if( isset( $request->registration_type ) )
        {
            switch( $request->registration_type )
            {
                case User::FACEBOOK || User::GOOGLE:
                    // validation
                    $this->validateEmail($request);

                    $user = User::find($request->email);

                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    break;
                case User::PHONE:
                    // validation
                    $this->validatePhone($request);

                    return $this->attemptLogin($request);
                    break;
                default:
                    // validation
                    $this->validateLogin($request);
                    return $this->attemptLogin($request);
                    break;
            }
        }
    }

    /**
     * Method validatePhone
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function validatePhone(Request $request)
    {
        $request->validate([
            'phone1' => 'required|number',
            'password' => 'required|string',
        ]);
    }

    /**
     * Method validateEmail
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string'
        ]);
    }
}