<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Progress;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
	private $user;

	private function display_successful_login_message(){
		session()->put('loginSuccess', true);
	}

	private function display_announcement_and_update_last_announcement(){
		session()->put('show_announcement', true);
		User::findOrFail($this->user->id)->update(['last_announcement' => Carbon::now()]);
	}

	public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (Auth::check()) {
            $this->user = Auth::user();

			// If the user just logged in, then show the announcement
			$current_time = Carbon::now();
			$last_login_time = Carbon::parse($this->user->last_login);

			if ($last_login_time->diffInSeconds($current_time) < 2) {
				$this->display_announcement_and_update_last_announcement();
				$this->display_successful_login_message();
			}

			// If not (from other request), redisplay announcement after 1 hour since last announcement show time
			else {
				$last_time = Carbon::parse($this->user->last_announcement);
				$current_time = Carbon::now();

				$time_diff = $last_time->diffInHours($current_time);

				if ($time_diff >= 1) {
					$this->display_announcement_and_update_last_announcement();
				} else {
					session()->put('show_announcement', false);
				}
			}
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('home');
        }
    }
}
