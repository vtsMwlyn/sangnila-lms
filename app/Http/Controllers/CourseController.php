<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\MaterialProgress;
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
		// $data = $request->all();
		// $course = new Course();
		// $course->course_name = $request->course_name;
		// $course->course_description = $request->course_desc;
		// $course->visibility = $request->visibility;
		// $course->save();
		// return redirect(route('sysadmin.course.index'));

		$validatedData = $request->validate([
			"course_name" => "required|min:3",
			"course_description" => "required|min:3",
			"visibility" => "required"
		]);

		Course::create($validatedData);

		return redirect(route('admin.course.index'))->with("successCreateNewCourse", "Successfully created new course!");
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
		Course::where('id', $course_id)->update($data);
		return redirect(route('sysadmin.course.show', $course_id))->with("successUpdateCourseData", "Successfully updated course data!");
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
		$courses = Course::get();
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
		$course = Course::findOrFail($course_id);
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
		$course = Course::/*where('visibility', 'public')->*/where('id', $course_id)->first();
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
		// $data = $request->except(['_token', '_method']);

		$validatedData = $request->validate([
			"course_name" => "required|min:3",
			"course_description" => "required|min:3",
			"visibility" => "required"
		]);

		// $course = Course::/*where('visibility', 'public')->*/where('id', $course_id)->update($data);
		Course::where('id', $course_id)->update($validatedData);

		return redirect(route('admin.course.show', $course_id))->with("successUpdateCourseData", "Successfully updated course data!");
	}

	public function admin_delete($course_id){
		$course = Course::where('id', $course_id)->first();

		return view('roles.admin.course.destroy', [
			'course' => $course,
		]);
	}

	public function admin_destroy($course_id){
		$course = Course::findOrFail($course_id);
		Course::destroy("id", $course->id);

		return redirect(route('admin.course.index'))->with("successDeleteCourse", "Successfully deleted course!");
	}

	// ========== TEACHER ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_index() {
		return view('roles.teacher.mycourse.index', []);
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

	// ========== STUDENT ==========

	public function student_index() {
		return view('roles.student.course.index');
	}

	public function student_show($course_id) {
		$materialProgresses = MaterialProgress::where('course_id', $course_id)->where('student_id', Auth::user()->id)->get();
		$student = CourseStudent::where('user_id', Auth::user()->id)->where('course_id', $course_id)->first();
		return view('roles.student.course.show', [
			'course' => $student->course,
			'materialProgresses' => $materialProgresses
		]);
	}
}
