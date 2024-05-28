<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\MaterialProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {

	// ========== ADMIN ==========

	public function admin_index() {
		$courses = Course::get();
		return view('roles.admin.course.index', [
			'courses' => $courses,
		]);
	}

	public function admin_create() {
		return view('roles.admin.course.create');
	}

	public function admin_store(Request $request) {
		$validatedData = $request->validate([
			"course_name" => "required|min:3",
			"course_description" => "required|min:3",
			"visibility" => "required"
		]);

		Course::create($validatedData);

		return redirect(route('admin.course.index'))->with("successCreateNewCourse", "Successfully created new course!");
	}

	public function admin_show($course_id) {
		$course = Course::findOrFail($course_id);
		return view('roles.admin.course.show', [
			'course' => $course
		]);
	}

	public function admin_edit($course_id) {
		$course = Course::/*where('visibility', 'public')->*/where('id', $course_id)->first();
		return view('roles.admin.course.edit', [
			'course' => $course
		]);
	}

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

	public function teacher_index() {
		return view('roles.teacher.mycourse.index', []);
	}

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
