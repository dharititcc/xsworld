<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Method sendResetResponse
     *
     * @param Request $request [explicite description]
     * @param $response $response [explicite description]
     *
     * @return mixed
     */
    protected function sendResetResponse(Request $request ,$response)
    {
        $user = User::where('email',$request->email)->first();
        if($user->user_type == User::CUSTOMER)
        {
            return redirect()->route('auths.password-change');
        }
        return redirect()->route('login');
    }
}
