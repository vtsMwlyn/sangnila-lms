<?php

namespace App\Http\Controllers;

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
	public function teacher_index() {
		$role = Role::where('role_name', 'Student')->first();
		$students = $role->users;
		return view('roles.teacher.student.index', [
			'students' => $students
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_show($student_id) {
		$student = User::findOrFail($student_id);
		return  view('roles.teacher.student.show', [
			'student' => $student
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
}
