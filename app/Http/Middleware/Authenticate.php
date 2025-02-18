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

			// Always showing notification for teacher accounts
			// if(Auth::user()->role_id == 2){
			// 	$all_course_has_topics = true;
			// 	$there_is_student_with_no_progress_unlocked = false;

			// 	// Check if cached values exist
			// 	if (!Session::has('all_course_has_topics') || !Session::has('there_is_student_with_no_progress_unlocked')) {
			// 		foreach (Auth::user()->teached_courses as $c) {
			// 			if ($c->topics->count() == 0) {
			// 				$all_course_has_topics = false;
			// 				break;
			// 			}
			// 		}

			// 		foreach (Auth::user()->teached_courses as $course) {
			// 			$students = CourseStudent::where('teacher_id', Auth::user()->id)
			// 				->where('course_id', $course->id)
			// 				->get();

			// 			foreach ($students as $student) {
			// 				$progress_statuses = Progress::where("course_id", $course->id)
			// 					->where("student_id", $student->student_id)
			// 					->pluck("status");

			// 				if ($progress_statuses->isEmpty() || !$progress_statuses->contains("unlocked")) {
			// 					$there_is_student_with_no_progress_unlocked = true;
			// 					break 2;
			// 				}
			// 			}
			// 		}

			// 		// Store results in the session for later use
			// 		Session::put('all_course_has_topics', $all_course_has_topics);
			// 		Session::put('there_is_student_with_no_progress_unlocked', $there_is_student_with_no_progress_unlocked);
			// 	}
			// }
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
