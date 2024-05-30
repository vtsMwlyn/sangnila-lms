<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\AssignmentSubmission;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller {

	// ===== TEACHER ===== //
	// Showing all assigned courses to the teacher to select before continue
	public function teacher_select_course() {
		return  view('roles.teacher.student.select-course', [
			"courses" => auth()->user()->teached_courses
		]);
	}

	// Showing all students in the selected course to select before continue
	public function teacher_select_student($course_id) {
		$course = Course::where("id", $course_id)->first();
		$students = $course->students;
		return view('roles.teacher.student.select-student', [
			'students' => $students,
			"course" => $course
		]);
	}

	// ===== ADMIN ===== //
	// Showing list of all active students in Sangnila LMS
	public function admin_index() {
		$role = Role::where('role_name', 'Student')->first();
		$students = $role->users;

		$current_progress = [];
		$student_max_progress = [];
		$percentage = [];

		foreach($students as $student){
			$cp = [];
			$smp = [];
			$p = [];

			foreach ($student->enrolled_courses as $course){
				$all_progress_in_current_course = [];
				foreach($student->progress as $pgr){
					if($pgr->course_id == $course->id){
						array_push($all_progress_in_current_course, $pgr);
					}
				}

				$count = 0;
				foreach($all_progress_in_current_course as $curr_pgr){
					if($curr_pgr->status == "unlocked"){
						$count++;
					}
				}

				$cs_data = CourseStudent::where("user_id", $student->id)->where("course_id", $course->id)->first();
				$maximum_sessions = $cs_data->max_course_session;

				array_push($cp, $count);
				array_push($smp, $maximum_sessions);

				$percent = round((float)($count / $maximum_sessions) * 100);

				array_push($p, $percent);
			}

			array_push($current_progress, $cp);
			array_push($student_max_progress, $smp);
			array_push($percentage, $p);
		}

		return view('roles.admin.student.index', [
			'students' => $students,
			"current_progress" => $current_progress,
			"student_max_progress" => $student_max_progress,
			"percentage" => $percentage
		]);
	}

	// Shows the details of a student (courses enrolled, data, attendance & assignment summary)
	public function admin_show($student_id) {
		$student = User::findOrFail($student_id);

		//Counting assignments done
		$count_assignment_all = [];
		$count_assignment_col = [];

		foreach($student->enrolled_courses as $crs){
			$all_assignments_data = Assignment::where("course_id", $crs->id)->where("student_id", $student->id)->where("student_is_assigned", 1)->get();

			$n_asg_subm = 0;
			foreach($all_assignments_data as $assg){
				if($assg->submissions->count()){
					$n_asg_subm++;
				}
			}

			array_push($count_assignment_all, $all_assignments_data->count());
			array_push($count_assignment_col, $n_asg_subm);
		}

		//Counting attended sessions
		$students_attendances = [];
		$count_full_attendance = [];

		foreach($student->enrolled_courses as $course){
			$attendance_data_of_student = Attendance::where("course_id", $course->id)->where("student_id", $student->id)->whereNot("attendance_detail", "Account disabled")->get();

			$cs = CourseStudent::where("user_id", $student_id)->where("course_id", $course->id)->first();
			$maximum_sessions = $cs->max_course_session;

			array_push($count_full_attendance, $maximum_sessions);
			array_push($students_attendances, $attendance_data_of_student);
		}

		$count_attendance_col = [];
		foreach($students_attendances as $atd){
			$count_attendance = 0;
			foreach($atd as $a){
				if($a->is_attend){
					$count_attendance++;
				}
			}

			array_push($count_attendance_col, $count_attendance);
			// array_push($count_full_attendance, $atd->count());
		}

		//Return view with data
		return view('roles.admin.student.show', [
			'student' => $student,
			"attendance_if_full" => $count_full_attendance,
			"attended" => $count_attendance_col,
			"assignment_if_full" => $count_assignment_all,
			"done_assignment" => $count_assignment_col
		]);
	}


	// Update student's max sessions in a course
	public function admin_update_max_session(Request $request, $student_id, $course_id){
		$request->validate(
			[
				"max_course_session" . $student_id . $course_id => "required|integer|min:1"
			],
			[
				"max_course_session" . $student_id . $course_id . ".min" => "The number must be greater than 1."
			]
		);

		CourseStudent::where("course_id", $course_id)->where("user_id", $student_id)->update(["max_course_session" => $request["max_course_session" . $student_id . $course_id]]);

		$course = Course::where("id", $course_id)->first();

		return back()->with("successUpdateMaxSession", "Student's max course session in course " . $course->course_name . " has been updated successfully!");
	}

	// Edit student data page
	public function admin_edit($student_id){
		$student = User::findOrFail($student_id);

		return view("roles.admin.student.edit", [
			"student" => $student,
			"education_levels" => ["Elementary School", "Junior High School", "Senior High School", "College", "Professional"]
		]);
	}

	// Update the student data in the database
	public function admin_update(Request $request, $student_id){
		$student = User::findOrFail($student_id);

		$validationRule = [
			"full_name" => "required|min:3",
			"phone_number" => "nullable",
			"city_of_birth" => "nullable",
			"date_of_birth" => "nullable",

			"school_name" => "nullable|min:3",
			"student_level" => "nullable",
			"name_parent" => "nullable|min:3",
			"phone_parent" => "nullable"
		];

		$validator = Validator::make($request->all(), $validationRule);

        $validator->sometimes('phone_parent', ['min:9', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'], function ($input) {
            return true;
        });

        $validator->sometimes('phone_number', ['min:9', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'], function ($input) {
            return true;
        });

		$dataToUpdate = $validator->validate();

		User::where("id", $student->id)->update(["full_name" => $dataToUpdate["full_name"]]);

		unset($dataToUpdate["full_name"]);

		UserDetail::where("user_id", $student->id)->update($dataToUpdate);

		return redirect(route("admin.student.show", $student_id))->with("successUpdateStudentData", "Successfully updated student data!");
	}
}
