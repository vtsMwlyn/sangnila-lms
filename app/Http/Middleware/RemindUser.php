<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\StudentAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RemindUser
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
			if(Auth::user()->role_id == 2){
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

			else if(Auth::user()->role_id == 1){
				$allCourses = Course::where('status', 'active')->with(['teachers', 'students', 'learning_outcomes', 'curriculum_topics'])->get();

				$uncomplete_course_data = false;

				foreach($allCourses as $course){
					if($course->teachers->count() == 0 || $course->students->count() == 0 || $course->learning_outcomes->count() == 0 || $course->curriculum_topics->count() == 0){
						$uncomplete_course_data = true;
						break;
					}
				}

				$allStudents = User::where('role_id', 3)->where('status', 'enabled')->with('enrolled_courses')->get();
				$allTeachers = User::where('role_id', 2)->where('status', 'enabled')->with('teached_courses')->get();

				$some_students_not_assigned_to_course = false;
				$some_teachers_not_assigned_to_course = false;

				foreach($allStudents as $student){
					if($student->enrolled_courses->count() == 0 && $student->status == 'enabled'){
						$some_students_not_assigned_to_course = true;
						break;
					}
				}

				foreach($allTeachers as $teacher){
					if($teacher->teached_courses->count() == 0 && $teacher->status == 'enabled'){
						$some_teachers_not_assigned_to_course = true;
						break;
					}
				}

				Session::put('uncomplete_course_data', $uncomplete_course_data);
				Session::put('some_students_not_assigned_to_course', $some_students_not_assigned_to_course);
				Session::put('some_teachers_not_assigned_to_course', $some_teachers_not_assigned_to_course);
			}

			else if(Auth::user()->role_id == 3){
				$student = Auth::user();
				$some_assignments_not_submitted = false;

				$student_assignments = StudentAssignment::where("student_id", $student->id)->with('assignment.submissions')->get();

				foreach($student_assignments as $sa){
					if($sa->assignment->submissions->where('student_id', $student->id)->count() == 0){
						$some_assignments_not_submitted = true;
						break;
					}
				}

				Session::put('some_assignments_not_submitted', $some_assignments_not_submitted);
			}
        }

        return $next($request);
    }
}
