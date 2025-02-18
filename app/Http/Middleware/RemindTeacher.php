<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Progress;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RemindTeacher
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
        if (Auth::check()) {
            // Check if all courses have topics
            $all_course_has_topics = true;

            foreach (Auth::user()->teached_courses as $c) {
                if ($c->topics->count() == 0) {
                    $all_course_has_topics = false;
                    break;
                }
            }

            // Check if there is a student with no progress unlocked
            $there_is_student_with_no_progress_unlocked = false;

            foreach (Auth::user()->teached_courses as $course) {
                $students = CourseStudent::where('teacher_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->get();

                foreach ($students as $student) {
                    $progress_statuses = Progress::where("course_id", $course->id)
                        ->where("student_id", $student->student_id)
                        ->pluck("status");

                    if ($progress_statuses->isEmpty() || !$progress_statuses->contains("unlocked")) {
                        $there_is_student_with_no_progress_unlocked = true;
                        break 2; // Exit both loops immediately
                    }
                }
            }

            // Store the computed values in the session
            Session::put('all_course_has_topics', $all_course_has_topics);
            Session::put('there_is_student_with_no_progress_unlocked', $there_is_student_with_no_progress_unlocked);
        }

        return $next($request);
    }
}
