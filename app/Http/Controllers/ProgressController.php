<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use App\Models\CourseStudent;
use App\Models\Progress;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller {
	// ===== SYSADMIN ONLY ===== //
	public function generate_progress_for_all_student(){
		try {
			DB::beginTransaction();
			
			foreach(User::where('role_id', 3)->get() as $student){
				foreach($student->enrolled_courses as $course){
					$teacher = CourseStudent::where('course_id', $course->id)->where('student_id', $student->id)->first()->teacher;
	
					$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
						return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
					})->orderBy('session', 'asc')->get();
	
					$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
						return $query->where('course_id', $course->id);
					})->get();
			
					$session_counter = 1;
	
					foreach($activities as $index => $activity){
						if($activity->session != $session_counter){
							$session_counter++;
						}
	
						Progress::updateOrCreate([
							"student_id" => $student->id,
							"course_id" => $course->id,
							"activity_id" => $activity->id,
						],
						[
							"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
						]);
					}
				}
				
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			throw $e;
		}
	}
}
