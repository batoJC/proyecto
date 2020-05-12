<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class DuenoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()) {
            if (Auth::user()->id_rol != 3) {

                return redirect('/home');
            }
            session(['conjunto' => Auth::user()->id_conjunto]);

            return $next($request);
        } else {
            return redirect('/login');
        }
    }
}
