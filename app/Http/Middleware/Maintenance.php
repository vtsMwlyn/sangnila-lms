<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		if((Auth::check() && Auth::user()->role_id == 1 && Auth::user()->email == "sysadmin@sangnilaindonesia.com") || (Auth::check() && Auth::user()->role_id == 2 && Auth::user()->email == "vannestheo.sangnila@gmail.com")){
			return $next($request);
		} else {
			return response()->view('web-status.under-maintenance', [], 403);
		}

    }
}
