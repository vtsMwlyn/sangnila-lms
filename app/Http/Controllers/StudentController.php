<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Role;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller {

	// ========== Teacher ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_select_course() {
		return  view('roles.teacher.student.select-course', [
			"courses" => auth()->user()->teached_courses
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_select_student($course_id) {
		$course = Course::where("id", $course_id)->first();
		$students = $course->students;
		return view('roles.teacher.student.select-student', [
			'students' => $students,
			"course" => $course
		]);
	}


	// ========== Admin ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin_index() {
		$role = Role::where('role_name', 'Student')->first();
		$students = $role->users;
		return view('roles.admin.student.index', [
			'students' => $students
		]);
	}

	public function admin_show($student_id) {
		$student = User::findOrFail($student_id);

		//Counting assignments done
		$count_assignment_all = [];
		$count_assignment_col = [];

		foreach($student->enrolled_courses as $crs){
			$all_assignments_data = StudentAssignment::where("course_id", $crs->id)->where("student_id", $student->id)->where("student_is_assigned", 1)->get();

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
			$attendance_data_of_student = StudentAttendance::where("course_id", $course->id)->where("student_id", $student->id)->get();

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
			array_push($count_full_attendance, $atd->count());
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

	public function admin_edit($student_id){
		$student = User::findOrFail($student_id);

		return view("roles.admin.student.edit", [
			"student" => $student
		]);
	}

	public function admin_update(Request $request, $student_id){
		$student = User::findOrFail($student_id);

		// $dataToUpdate = $request->except(["_token", "_method"]);

		$dataToUpdate = $request->validate([
			"full_name" => "required|min:3",
			"email" => "required|email:dns"
		]);

		User::where("id", $student->id)->update($dataToUpdate);

		return redirect(route("admin.student.index"))->with("successUpdateStudentData", "Successfully updated student data!");
	}
}
