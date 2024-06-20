<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Log;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
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
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

		return view('roles.teacher.student.select-student', [
			'course_students' => $course_students,
			"course" => $course
		]);
	}

	// ===== ADMIN ===== //
	// Showing list of all active students in Sangnila LMS
	public function admin_index() {
		$students = User::where("role_id", 3)->filter(request(["search"]))->get();

		$max_attendances = [];
		$current_attendances = [];
		$percentages = [];
		$studentList = [];

		// To contain prioritized students with current attendance of max attendance - 1
		$max_attendances2 = [];
		$current_attendances2 = [];
		$percentages2 = [];
		$studentList2 = [];

		foreach($students as $student){
			$maiscec = []; //max attendances in student current enrolled course
			$caiscec = []; //current attendances in student current enrolled course
			$piscec = []; //percentage in student current enrolled course

			$prioritized = false;

			$sa = StudentAttendance::where("user_id", $student->id)->get();

			foreach($student->enrolled_courses as $course){
				// Number of attendances (separated for imported students and unimported students)
				$cs = CourseStudent::where("course_id", $course->id)->where("student_id", $student->id)->first();

				if($cs->is_imported){
					$count = ImportedStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->last_attendance_count;
				} else {
					$count = 0;
				}

				foreach($sa as $atd){
					if($atd->attendance->course_id == $course->id && $atd->is_attend == 1){
						$count++;
					}
				}

				if((($count + 1) % $cs->max_course_session == 0) || $count == $cs->max_course_session){
					$prioritized = true;
				}

				array_push($maiscec, $cs->max_course_session);
				array_push($caiscec, $count);
				array_push($piscec, round((float)($count / $cs->max_course_session) * 100));

			}

			if($prioritized){
				array_push($max_attendances2, $maiscec);
				array_push($current_attendances2, $caiscec);
				array_push($percentages2, $piscec);
				array_push($studentList2, $student);
			} else {
				array_push($max_attendances, $maiscec);
				array_push($current_attendances, $caiscec);
				array_push($percentages, $piscec);
				array_push($studentList, $student);
			}

		}

		$students = collect(array_merge($studentList2, $studentList));
		$current_attendances = array_merge($current_attendances2, $current_attendances);
		$max_attendances = array_merge($max_attendances2, $max_attendances);
		$percentages = array_merge($percentages2, $percentages);

		// Return view with data
		return view('roles.admin.student.index', [
			'students' => $students,
			"max_attendances" => $max_attendances,
			"current_attendances" => $current_attendances,
			"percentages" => $percentages
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
		$count_curr_progress = [];
		$count_full_progress = [];

		foreach($student->enrolled_courses as $course){
			$cs = CourseStudent::where("course_id", $course->id)->where("student_id", $student_id)->first();
			array_push($count_full_progress, $cs->max_course_session);

			$progresses = Progress::where("course_id", $course->id)->where("student_id", $student_id)->get();

			$count = 0;
			foreach($progresses as $progress){
				if($progress->status == "unlocked"){
					$count++;
				}
			}
			array_push($count_curr_progress, $count);
		}

		//Return view with data
		return view('roles.admin.student.show', [
			'student' => $student,
			"full_progress" => $count_full_progress,
			"current_progress" => $count_curr_progress,
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
