<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Role;
use Illuminate\Http\Request;

class CourseTeacherController extends Controller {
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
	public function create($course_id) {
		// Find the course
		$course = Course::findOrFail($course_id);

		// Find Teacher role
		$role = Role::where('role_name', 'Teacher')->first();

		// Get the user IDs that are already associated with the course
		$existingUserIds = CourseTeacher::where('course_id', $course_id)->get()->pluck('user_id')->toArray();

		// Find users with the "Teacher" role who are not already associated with the course
		$teachers = $role->users()
			->whereNotIn('id', $existingUserIds)
			->get();
		return view('roles.admin.teacher.assign', [
			'teachers' => $teachers,
			'course' => $course
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $course_id) {
		$data = $request->all();

		$lecture = new CourseTeacher();
		$lecture->user_id = $request->teacher;
		$lecture->course_id = $course_id;
		$lecture->save();
		return redirect(route('admin.course.show', $course_id));
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
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	//================================================================

	/**
	 * Unassign the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function unassign($course_id) {
		$course = Course::findOrFail($course_id);
		$teachers = $course->teachers;
		// return view(admin.course.unassign);
		//
		// TODO
	}



	/**
	 * Unassign the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function unassign_destroy($course_id, Request $request) {
		$teacher_id = $request->teacher_id;
		$lecture = CourseTeacher::where('course_id', $course_id)->where('user_id', $teacher_id)->first();
		dd($lecture);
		CourseTeacher::destroy($lecture->id);
		return redirect(route('admin.course.show', $course_id));
	}
}
