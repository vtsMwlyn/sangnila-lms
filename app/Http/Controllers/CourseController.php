<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\Progress;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {
	// ===== ADMIN ===== //
	// Showing list of all available courses in Sangnila LMS
	public function admin_index() {
		$courses = Course::filter(request(["search"]))->get();
		return view('roles.admin.course.index', [
			'courses' => $courses,
		]);
	}

	// Create new course page
	public function admin_create() {
		return view('roles.admin.course.create');
	}

	// Insert the new course into database
	public function admin_store(Request $request) {
		$validatedData = $request->validate([
			"course_name" => "required|min:3",
			"course_description" => "required|min:3",
			"visibility" => "required"
		]);

		Course::create($validatedData);

		return redirect(route('admin.course.index'))->with("successCreateNewCourse", "Successfully created new course!");
	}

	// Shows a course details
	public function admin_show($course_id) {
		$course = Course::findOrFail($course_id);
		return view('roles.admin.course.show', [
			'course' => $course
		]);
	}

	// Edit course page
	public function admin_edit($course_id) {
		$course = Course::/*where('visibility', 'public')->*/where('id', $course_id)->first();
		return view('roles.admin.course.edit', [
			'course' => $course
		]);
	}

	// Update the course in the database
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

	// Course deletion confirmation
	public function admin_delete($course_id){
		$course = Course::where('id', $course_id)->first();

		return view('roles.admin.course.destroy', [
			'course' => $course,
		]);
	}

	// Delete course from database
	public function admin_destroy($course_id){
		$course = Course::findOrFail($course_id);
		Course::destroy("id", $course->id);

		return redirect(route('admin.course.index'))->with("successDeleteCourse", "Successfully deleted course!");
	}


	// ===== TEACHER ====== //
	// List of assigned courses
	public function teacher_index() {
		return view('roles.teacher.mycourse.index', []);
	}

	// Shows a course details also topics and materials
	public function teacher_show($course_id) {
		$course = Course::where("id", $course_id)->first();
		$course_students = CourseStudent::where("teacher_id", Auth::user()->id)->where("course_id", $course_id)->get();

		return view('roles.teacher.mycourse.show', [
			'course_students' => $course_students,
			"course" => $course
		]);
	}


	// ===== STUDENT ===== //
	// List of enrolled courses
	public function student_index() {
		$enrolled_courses = Auth::user()->enrolled_courses;
		$paymentReminders = [];

		foreach($enrolled_courses as $c){
			$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $c->id)->first();

			if($cs->is_imported){
				$count = ImportedStudent::where("course_id", $c->id)->where("student_id", Auth::user()->id)->first()->last_attendance_count;
			} else {
				$count = 0;
			}

			$stdatd = StudentAttendance::where("user_id", Auth::user()->id)->get();

			foreach($stdatd as $atd){
				if($atd->attendance->course_id == $c->id && $atd->is_attend == 1){
					$count++;
				}
			}

			if((($count + 1) % $cs->max_course_session == 0) || $count >= $cs->max_course_session){
				$shouldPaySoon = true;
			} else {
				$shouldPaySoon = false;
			}

			array_push($paymentReminders, ["course" => $c->course_name, "should_pay_soon" => $shouldPaySoon]);
		}

		return view('roles.student.course.index', [
			"payment_reminders" => $paymentReminders
		]);
	}

	// Shows a course details with topics and materials
	public function student_show($course_id) {
		$progresses = Progress::where('course_id', $course_id)->where('student_id', Auth::user()->id)->get();
		$student = CourseStudent::where('student_id', Auth::user()->id)->where('course_id', $course_id)->first();
		return view('roles.student.course.show', [
			'course' => $student->course,
			'materialProgresses' => $progresses
		]);
	}


}
