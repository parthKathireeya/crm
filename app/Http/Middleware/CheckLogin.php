<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('authentication.login') && session('authentication.login') === true) {
            return $next($request);
        }

        // session()->flash('toastr_error', 'You must be logged in to access this page.');
        return redirect()->route('login');
    }
}
