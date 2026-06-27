<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    if (Auth::check() && Auth::user()->role === 'manager') {
        return $next($request);
    }

    return redirect('/dashboard')->with('error', 'Access denied.');
}
}
