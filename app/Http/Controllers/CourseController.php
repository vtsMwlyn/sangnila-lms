<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller {

	// SYSADMIN==============================================================
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sys_index() {
		$courses = Course::get();
		dd($courses);
		// return view(sysadmin.course.index);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sys_create() {
		// return view(sysadmin.course.create);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function sys_store(Request $request) {
		$data = $request->all();
		dd($data);
		$course = new Course();
		$course->course_name = $request->course_name;
		$course->course_description = $request->course_description;
		$course->visibility = $request->course_visibility;
		$course->save();
		return redirect(route('home'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_show($course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// return view(sysadmin.course.show);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_edit($course_id) {
		$course = Course::findOrFail( $course_id);
		dd($course);
		// return edit form
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_update(Request $request, $course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// Update Course DB
	}

	/**
	 * Show the confirmation for archiving the specified resource.
	 *
	 * FOR SYSADMIN
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function sys_archive_confirm($course_id) {
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
	public function sys_archive_update($course_id) {
		$course = Course::findOrFail($course_id);
		$course->visibility = 'private';
		dd($course);
		$course->save();
		return redirect(route('sys.course.inde'));
	}

	// ADMIN ==============================================================
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin__index() {
		$courses = Course::get();
		dd($courses);
		// return view(admin.course.index);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_show($course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// return view(admin.course.show);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_edit($course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// return edit form
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function admin_update(Request $request, $course_id) {
		$course = Course::findOrFail($course_id);
		dd($course);
		// Update Course DB
	}

}
