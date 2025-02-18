<?php

namespace App\Providers;

use App\Models\Progress;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Check if the user is logged in using the remember me option
		Auth::viaRequest('remember', function ($request) {
			// Run the logic only if the user is authenticated
			if (Auth::check()) {
				$user = Auth::user();

				// If the required values are not already cached or in session
				if (!Session::has('all_course_has_topics') || !Session::has('there_is_student_with_no_progress_unlocked')) {

					$all_course_has_topics = true;
					$there_is_student_with_no_progress_unlocked = false;

					// Logic to check if all courses have topics
					foreach ($user->teached_courses as $c) {
						if ($c->topics->count() == 0) {
							$all_course_has_topics = false;
							break;
						}
					}

					// Logic to check if there are students with no unlocked progress
					foreach ($user->teached_courses as $course) {
						$students = CourseStudent::where('teacher_id', $user->id)
							->where('course_id', $course->id)
							->get();

						foreach ($students as $student) {
							$progress_statuses = Progress::where("course_id", $course->id)
								->where("student_id", $student->student_id)
								->pluck("status");

							if ($progress_statuses->isEmpty() || !$progress_statuses->contains("unlocked")) {
								$there_is_student_with_no_progress_unlocked = true;
								break 2;
							}
						}
					}

					// Store values in the session
					Session::put('all_course_has_topics', $all_course_has_topics);
					Session::put('there_is_student_with_no_progress_unlocked', $there_is_student_with_no_progress_unlocked);
				}
			}

			return $user;
		});
    }
}
