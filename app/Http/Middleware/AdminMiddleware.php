<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request [explicite description]
     * @param Closure $next [explicite description]
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( !auth()->check() )
        {
            return redirect()->route('admin.auth.login');
        }

        if( auth()->check() && access()->isRestaurantOwner() )
        {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
