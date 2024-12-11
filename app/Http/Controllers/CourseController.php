<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\CurriculumTopic;
use App\Models\ImportedStudent;
use App\Models\LearningOutcome;
use App\Models\Progress;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {
	// ===== ADMIN ===== //
	// Showing list of all available courses in Sangnila LMS
	public function admin_index() {
		return view('roles.admin.course.index', [
			'courses' => Course::filter(request(["search"]))->get()
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
			"status" => "required",
			"format" => "required",
			"level" => "required"
		]);

		try {
			Course::create($validatedData);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create course, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.index'))->with("successCreateNewCourse", "Successfully created new course!");
	}

	// Shows a course details
	public function admin_show($course_id) {
		$course = Course::findOrFail($course_id);
		return view('roles.admin.course.show', [
			'course' => $course,
			'learning_outcomes' => LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get()
		]);
	}

	// Edit course page
	public function admin_edit($course_id) {
		return view('roles.admin.course.edit', [
			'course' => Course::findOrFail($course_id)
		]);
	}

	// Update the course in the database
	public function admin_update(Request $request, $course_id) {
		$validatedData = $request->validate([
			"course_name" => "required|min:3",
			"course_description" => "required|min:3",
			"status" => "required",
			"format" => "required",
			"level" => "required"
		]);

		try {
			Course::findOrFail($course_id)->update($validatedData);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit course, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.show', $course_id))->with("successUpdateCourseData", "Successfully updated course data!");
	}

	// Course deletion confirmation
	public function admin_delete($course_id){
		return view('roles.admin.course.destroy', [
			'course' => Course::findOrFail($course_id)
		]);
	}

	// Delete course from database
	public function admin_destroy($course_id){
		Course::findOrFail($course_id)->delete();

		return redirect(route('admin.course.index'))->with("successDeleteCourse", "Successfully deleted course!");
	}


	// ===== TEACHER ====== //
	// List of assigned courses
	public function teacher_index() {
		return view('roles.teacher.mycourse.index', []);
	}

	// Shows a course details also topics and activities
	public function teacher_show($course_id) {
		$course = Course::findOrFail($course_id);
		$course_students = CourseStudent::where("teacher_id", Auth::user()->id)->where("course_id", $course_id)->get();
		$curriculum = CurriculumTopic::where("course_id", $course->id)->get();

		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();
		$learning_outcomes = LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get();

		return view('roles.teacher.mycourse.show', [
			'course_students' => $course_students,
			"course" => $course,
			"topics" => $topics,
			"has_curriculum" => $curriculum->count(),
			"learning_outcomes" => $learning_outcomes
		]);
	}

	// ===== STUDENT ===== //
	// List of enrolled courses
	public function student_index() {
		// Check for every course is the student's attendance reaching its max session
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

		// $pushNotif = new PushNotificationController();
		// $pushNotif->sendPushNotification();

		return view('roles.student.course.index', [
			"payment_reminders" => $paymentReminders
		]);
	}

	// Shows a course details with topics and activities
	public function student_show($course_id) {
		// Checking if total attendances near or reaching the course max session
		$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $course_id)->first();

		if($cs->is_imported){
			$count = ImportedStudent::where("course_id", $course_id)->where("student_id", Auth::user()->id)->first()->last_attendance_count;
		} else {
			$count = 0;
		}

		$stdatd = StudentAttendance::where("user_id", Auth::user()->id)->get();

		foreach($stdatd as $atd){
			if($atd->attendance->course_id == $course_id && $atd->is_attend == 1){
				$count++;
			}
		}

		$shouldPaySoon = false;
		$max_session_reached = false;
		if(($count + 1) % $cs->max_course_session == 0){
			$shouldPaySoon = true;
		} else if($count >= $cs->max_course_session) {
			$shouldPaySoon = true;
			$max_session_reached = true;
		}

		// Other data
		$progresses = Progress::where('course_id', $course_id)->where('student_id', Auth::user()->id)->get();

		$progressAndActivity = [];
		foreach($progresses as $prgs){
			$pam = [];
			$pam["progress"] = $prgs;
			$pam["activity"] = $prgs->activity;
			$pam["topic"] = $prgs->activity->topic;
			$pam["learning_outcomes"] = $prgs->activity->learning_outcomes->toJson();
			array_push($progressAndActivity, $pam);
		}

		$student = CourseStudent::where('student_id', Auth::user()->id)->where('course_id', $course_id)->first();

		if($max_session_reached){
			return view('roles.student.course.show', [
				'course' => $student->course,
				"should_pay_soon" => $shouldPaySoon,
				"max_session_reached" => $max_session_reached
			]);
		}
		else {
			return view('roles.student.course.show', [
				'course' => $student->course,
				'activityProgresses' => $progressAndActivity,
				"should_pay_soon" => $shouldPaySoon,
				"max_session_reached" => $max_session_reached
			]);
		}
	}


}
