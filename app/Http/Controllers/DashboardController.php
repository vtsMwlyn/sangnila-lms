<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\Progress;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function index(){
		if(Auth::check()){
			$role = Auth::user()->role->id;

			if($role == 1){
				return $this->admin_dashboard();
			}
			elseif($role == 2) {
				return $this->teacher_dashboard();
			}
			elseif($role == 3) {
				return $this->student_dashboard();
			}
		}
		else {
			return view("roles.guest.home");
		}
	}

    public function admin_dashboard(){
		return view("roles.admin.dashboard");
	}

	public function teacher_dashboard(){
		return view("roles.teacher.dashboard");
	}

	public function student_dashboard(){
		// Todo
		$student = Auth::user();
		$undone_asg = [];

		foreach($student->enrolled_courses as $crs){
			$student_assignments = StudentAssignment::where("student_id", $student->id)->get();

			$student_assignments_in_the_course = [];
			foreach($student_assignments as $asg){
				if($asg->assignment->course_id == $crs->id){
					array_push($student_assignments_in_the_course, $asg);
				}
			}

			foreach($student_assignments_in_the_course as $assg){
				$already_submit = false;
				foreach($assg->assignment->submissions as $submission){
					if($submission->student_id == $student->id){
						$already_submit = true;
						break;
					}
				}

				if(!$already_submit){
					array_push($undone_asg, $assg);
				}
			}
		}

		// Progress
		$course_students = CourseStudent::where("student_id", Auth::user()->id)->get();
		$student_attendances = StudentAttendance::where("student_id", Auth::user()->id)->get();

		$atd_progress = [];
		$mtr_progress = [];

		$assignments_done = 0;

		foreach($course_students as $cs){
			// Attendance progress
			$current_and_full = [];

			$n_present = 0;
			foreach($student_attendances as $sa){
				if($sa->attendance->course_id == $cs->course_id /*&& $sa->is_attend == 1*/){
					$n_present++;
				}
			}

			array_push($current_and_full, $n_present);
			array_push($current_and_full, $cs->max_course_session);
			array_push($atd_progress, $current_and_full);

			// Activity progress
			$unlocked_and_full = [];

			$n_all_activities = 0;
			$topics = Topic::where("user_id", $cs->teacher_id)->where("course_id", $cs->course_id)->get();
			if($topics->count()){
				foreach($topics as $topic){
					if($topic->activities->count()){
						$n_all_activities += $topic->activities->count();
					}
				}
			}

			$n_unlocked = 0;
			$unlocked_activities = Progress::where("student_id", Auth::user()->id)->where("course_id", $cs->course_id)->get();
			foreach($unlocked_activities as $unl){
				if($unl->status == "unlocked"){
					$n_unlocked++;
				}
			}

			array_push($unlocked_and_full, $n_unlocked);
			array_push($unlocked_and_full, $n_all_activities);
			array_push($mtr_progress, $unlocked_and_full);

			$assignments = Assignment::where("course_id", $cs->course_id)->where("teacher_id", $cs->teacher_id)->get();
			foreach($assignments as $asg){
				if($asg->submissions->count()){
					foreach($asg->submissions as $submission){
						if($submission->student_id == Auth::user()->id){
							$assignments_done++;
							break;
						}
					}
				}
			}

		}

		$courses_enrolled = $course_students->count();
		$sessions_attended = 0;
		foreach($atd_progress as $i => $ap){
			$sessions_attended += $ap[0];
		}
		$activities_unlocked = 0;
		foreach($mtr_progress as $i => $mp){
			$activities_unlocked += $mp[0];
		}

		return view("roles.student.dashboard", [
			"courses_enrolled" => $courses_enrolled,
			"sessions_attended" => $sessions_attended,
			"assignments_done" => $assignments_done,
			"activities_unlocked" => $activities_unlocked,

			"undone_assignment" => $undone_asg,
			"activity_progress" => $mtr_progress,
			"attendance_progress" => $atd_progress,
			"course_students" =>  $course_students
		]);
	}
}
