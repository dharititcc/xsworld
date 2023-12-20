<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class XSWorldVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param string $token
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function verify(string $token)
    {
        if( !isset( $token ) )
        {
            abort(404);
        }

        $user = User::query()->select(['id', 'email', 'verification_code'])->where('verification_code', $token)->first();

        if( !isset($user->id) )
        {
            return redirect()->route('auth.token-expiry');
        }

        if(isset($user->email_verified_at))
        {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return redirect()->route('auth.verification-success', ['token' => $token]);
    }

    /**
     * Method verificationSuccess
     *
     * @param string $token [explicite description]
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function verificationSuccess(string $token)
    {
        if( !isset($token) )
        {
            abort(404);
        }

        $user = User::query()->select(['id', 'email', 'verification_code'])->where('verification_code', $token)->first();

        if( !isset($user->id) )
        {
            abort(404, 'Your verification link has been expired.');
        }

        $user->verification_code = null;
        $user->save();

        return view('auth.verification');
    }

    /**
     * Method tokenExpiry
     *
     * @return view
     */
    public function tokenExpiry()
    {
        return view('auth.token-expiry');
    }

    /**
     * Method verificationSuccess
     *
     * @return view
     */
    public function passwordChange()
    {
        return view('auth.password-success');
    }
}
