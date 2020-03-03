<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;


class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user() || !$request->user()->phone_verified_at) {
            return Redirect::route('verify');
        }

        return $next($request);
    }
}
