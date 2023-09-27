<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait Authenticate
{
    use AuthenticatesUsers;

    /**
     * Method authenticate
     *
     * @param Request $request [explicite description]
     *
     * @return bool|array
     */
    public function authenticate(Request $request)
    {
        return $this->getLoginType($request);
    }

    /**
     * Method getLoginType
     *
     * @param Request $request [explicite description]
     *
     * @return bool|array
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

                    $user = User::where('email', $request->email)->first();
                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    else
                    {
                        // insert
                        return ['is_first_time' => 1];
                    }
                    break;
                case User::FACEBOOK:
                    // validation
                    $this->validateEmail($request);

                    $user = User::where('email', $request->email)->first();

                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    else
                    {
                        // insert
                        return ['is_first_time' => 1];
                    }
                    break;
                case User::PHONE:
                    // validation
                    $this->validatePhone($request);

                    return Auth::attempt($this->credentials($request));
                    break;
                case User::USERNAME:
                    // validation
                    $this->validateUsername($request);

                    return Auth::attempt($this->credentials($request));
                    break;
                case User::APPLE:
                    $user = User::where('social_id', $request->social_id)->first();
                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    else
                    {
                        // insert
                        return ['is_first_time' => 1];
                    }
                    break;
                default:
                    // validation
                    $this->validateEmail($request);
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
     * Method validateUsername
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function validateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
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
        // $username = $request->get($this->username($request));

        // check if the value is a validate email address and assign the field name accordingly
        $field = $this->username($request);//filter_var($username, FILTER_VALIDATE_EMAIL) ? $this->username($request)  : 'phone';

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
    public function username(Request $request): string
    {
        // $field = filter_var(request()->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        // return $field;
        $type = intval($request->registration_type);
        switch( $type )
        {
            case User::GOOGLE:
                return 'email';
                break;
            case User::FACEBOOK:
                return 'email';
                break;
            case User::PHONE:
                return 'phone';
                break;
            case User::USERNAME:
                return 'username';
                break;
            default:
                return 'email';
                break;
        }
    }

    /**
     * Method insertUser
     *
     * @param array $data [explicite description]
     *
     * @return \App\Models\User
     */
    private function insertUser(array $data): User
    {
        $user = User::create($data);

        $stripe                     = new Stripe();
        $customer                   = $stripe->createCustomer($data);

        $user->stripe_customer_id = $customer->id;
        $user->save();

        return $user;
    }

}