<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Role;
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


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_show($student_id) {
		$student = User::findOrFail($student_id);
		return  view('roles.admin.student.show', [
			'student' => $student
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
