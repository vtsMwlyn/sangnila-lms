<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Disabled
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
		if(auth()->user()->status != "disabled"){
			return $next($request);
		} else {
			switch(Auth::user()->role->id){
				case 1: return response()->view('roles.admin.account-disabled', [], 403);
				case 2: return response()->view('roles.teacher.account-disabled', [], 403);
				case 3: return response()->view('roles.student.account-disabled', [], 403);
			}

		}

    }
}
