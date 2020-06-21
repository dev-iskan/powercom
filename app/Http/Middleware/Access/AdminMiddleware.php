<?php

namespace App\Http\Middleware\Access;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->isAdmin()) return $next($request);
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
