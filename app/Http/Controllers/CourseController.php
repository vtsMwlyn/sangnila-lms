<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {

	// ========== SYSADMIN ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sys_index() {
		$courses = Course::get();
		return view('roles.sysadmin.course.index', [
			'courses' => $courses
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sys_create() {
		return view('roles.sysadmin.course.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function sys_store(Request $request) {
		$data = $request->all();
		$course = new Course();
		$course->course_name = $request->course_name;
		$course->course_description = $request->course_desc;
		$course->visibility = $request->visibility;
		$course->save();
		return redirect(route('sysadmin.course.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_show($course_id) {
		$course = Course::findOrFail($course_id);
		return view('roles.sysadmin.course.show', [
			'course' => $course
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_edit($course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// return view(sysadmin.course.edit);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_update(Request $request, $course_id) {
		$data = $request->except(['_token', '_method']);
		dd($data);
		Course::where('id', $course_id)->update($data);
		return redirect(route('sysadmin.course.show', [
			'course_id' => $course_id
		]));
	}

	/**
	 * Show the confirmation for archiving the specified resource.
	 *
	 * FOR SYSADMIN
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_archive_confirm($course_id, Request $request) {
		$course = Course::findOrFail($course_id);
		dd($course);

		//return view(sysadmin.course.archive);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * For SYSADMIN
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_archive_update($course_id, Request $request) {
		$visibility = $request->visibility === 'on' ? 'public' : 'private';
		$course = Course::findOrFail($course_id)->update(['visibility' => $visibility]);
		return redirect(route('sysadmin.course.index'));
	}

	// ========== ADMIN ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin_index() {
		$courses = Course::where('visibility', 'public')->get();
		return view('roles.admin.course.index', [
			'courses' => $courses,
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_show($course_id) {
		$course = Course::findOrFail($course_id)->where('visibility', 'public')->first();
		return view('roles.admin.course.show', [
			'course' => $course
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_edit($course_id) {
		$course = Course::findOrFail($course_id)->where('visibility', 'public')->first();
		return view('roles.admin.course.edit', [
			'course' => $course
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_update(Request $request, $course_id) {
		$data = $request->except(['_token', '_method']);
		$course = Course::where('visibility', 'public')->where('id', $course_id)->update($data);
		return redirect(route('admin.course.show', $course_id));
	}

	// ========== TEACHER ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_index() {
		$courses = Auth::user()->teached_courses;
		return view('roles.teacher.mycourse.index', [
			'courses' => $courses,
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_show($course_id) {
		$lecturer = CourseTeacher::where('user_id', Auth::user()->id)->where('course_id', $course_id)->first();
		return view('roles.teacher.mycourse.show', [
			'course' => $lecturer->course
		]);
	}
}
