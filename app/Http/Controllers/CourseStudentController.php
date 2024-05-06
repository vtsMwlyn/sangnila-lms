<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($student_id) {
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$existingCourseIds = CourseStudent::where('user_id', $student_id)->get()->pluck('course_id')->toArray();

		$courses = Course::where('visibility', 'public')
			->whereNotIn('id', $existingCourseIds)
			->get();

		// return view('roles.admin.student.assign', [
		// 	'student' => $student,
		// 	'courses' => $courses,
		// ]);

		return view('roles.admin.student.index-assign', [
			'student' => $student,
			'courses' => $courses,
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $student_id) {
		$targettedCourse = Course::where("course_name", $request["course_name"])->first();

		CourseStudent::create([
			'user_id' => $student_id,
			'course_id' => $targettedCourse->id
		]);

		return redirect(route('admin.student.show', $student_id))->with("successAssignToCourse", "Successfully assigned the student to the course!");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Show delete page for specific record
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete($student_id, $course_id) {
		$role = Role::where('role_name', 'student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		return view('roles.admin.student.destroy', [
			'student' => $student,
			'course' => $course
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($student_id, $course_id) {
		$CourseStudent = CourseStudent::where('user_id', $student_id)->where('course_id', $course_id)->first();
		CourseStudent::destroy($CourseStudent->id);
		return redirect(route('admin.student.show', $student_id))->with("successUnassignFromCourse", "Successfully unassigned the student from the course!");;
	}
}
