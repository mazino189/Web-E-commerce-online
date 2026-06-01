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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check user login and role admin //
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // if not admin, redirect to home or show error
        return redirect('/'); // or return response()->json(['error' => 'Unauthorized'], 403);
    }
}
