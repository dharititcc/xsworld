<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Exceptions\GeneralException;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @throws \App\Exceptions\GeneralException
     */
    public function getLoginType(Request $request)
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
                case User::PHONE:
                    // validation
                    $this->validatePhone($request);

                    return Auth::attempt($this->credentials($request));
                    break;
                default:
                    // validation
                    $this->validateLogin($request);
                    return Auth::attempt($this->credentials($request));
                    break;
            }
        }

        throw new GeneralException('Registration type field is required');
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
            'phone' => 'required|numeric',
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

    /**
     * Method credentials
     *
     * @param Request $request [explicite description]
     *
     * @return array
     */
    public function credentials(Request $request):array
    {
        // the value in the 'email' field in the request
        $username = $request->get($this->username());

        // check if the value is a validate email address and assign the field name accordingly
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? $this->username()  : 'phone';

        // return the credentials to be used to attempt login
        return [
            $field => $request->get($field),
            'password' => $request->password,
        ];
    }

    /**
     * Method username
     *
     * @return string
     */
    public function username(): string
    {
        $field = filter_var(request()->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        return $field;
    }
}