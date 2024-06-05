<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\AssignmentSubmission;
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

				$cs_data = CourseStudent::where("student_id", $student->id)->where("course_id", $course->id)->first();
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
			$student_assignments = StudentAssignment::where("student_id", $student_id)->get();

			$student_assignments_in_the_course = [];
			foreach($student_assignments as $asg){
				if($asg->assignment->course_id == $crs->id){
					array_push($student_assignments_in_the_course, $asg);
				}
			}

			$n_asg_subm = 0;
			foreach($student_assignments_in_the_course as $assg){
				foreach($assg->assignment->submissions as $submission){
					if($submission->student_id == $student_id){
						$n_asg_subm++;
						break;
					}
				}
			}

			array_push($count_assignment_all, count($student_assignments_in_the_course));
			array_push($count_assignment_col, $n_asg_subm);
		}

		//Counting attended sessions
		$count_curr_attendance = [];
		$count_full_attendance = [];

		foreach($student->enrolled_courses as $course){
			$attendance_data_in_the_course = Attendance::where("course_id", $course->id)->get();
			array_push($count_full_attendance, $attendance_data_in_the_course->count());

			$n = 0;
			foreach($attendance_data_in_the_course as $atd){
				$existingAttendances = $atd->student_attendances;

				foreach($existingAttendances as $sa){
					if($sa->user_id == $student_id && $sa->is_attend == 1){
						$n++;
						break;
					}
				}
			}

			array_push($count_curr_attendance, $n);
		}

		//Return view with data
		return view('roles.admin.student.show', [
			'student' => $student,
			"attendance_if_full" => $count_full_attendance,
			"attended" => $count_curr_attendance,
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

		CourseStudent::where("course_id", $course_id)->where("student_id", $student_id)->update(["max_course_session" => $request["max_course_session" . $student_id . $course_id]]);

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
