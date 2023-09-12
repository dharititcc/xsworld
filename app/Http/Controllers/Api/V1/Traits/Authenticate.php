<?php namespace App\Http\Controllers\Api\V1\Traits;

use App\Billing\Stripe;
use App\Exceptions\GeneralException;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

                    $user = User::where('email', $request->email)->first();
                    if(!$user){
                       $user = $this->socialRegister($request);
                    }
                    if( $user instanceof \App\Models\User && isset( $user->id ) )
                    {
                        auth()->login($user);
                        return true;
                    }
                    break;
                case User::FACEBOOK:
                    // validation
                    $this->validateEmail($request);

                    $user = User::where('email', $request->email)->first();
                    if(!$user){
                        $user = $this->socialRegister($request);
                    }

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
                case User::USERNAME:
                    // validation
                    $this->validateUsername($request);

                    return Auth::attempt($this->credentials($request));
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
     * Method socialRegister
     *
     * @param Request $request [explicite description]
     *
     * @return App\Models\User
     */
    public function socialRegister(Request $request) : User
    {
        $dataArr = [
            'first_name'            => $request->first_name ?? 'test',
            'last_name'             => $request->last_name ?? '',
            'email'                 => $request->email,
            'password'              => Hash::make(Str::random(6)),
            'phone'                 => $request->phone ?? rand(),
            // 'country_code'          => $request->country_code,
            // 'country_id'            => $request->country,
            // 'address'               => $request->address,
            'registration_type'     => $request->registration_type,
            // 'birth_date'            => $request->birth_date,
            'platform'              => $request->platform,
            'os_version'            => $request->os_version,
            'fcm_token'             => $request->fcm_token,
            'application_version'   => $request->application_version,
            'model'                 => $request->model,
            'user_type'             => User::CUSTOMER
        ];

        $user                       = User::create($dataArr);
        $stripe                     = new Stripe();
        $customer                   = $stripe->createCustomer($data);
        $str['stripe_customer_id']  = $customer->id;
        $user->update($str);

        return $user;
    }
}