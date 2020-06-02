<?php

namespace App\Http\Middleware\Access;

use Closure;
use Illuminate\Http\Request;

class GeneralAccessMiddleware
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
        $user = auth()->user();
        if ($user->isAdmin() || $user->isOperator()) return $next($request);

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
