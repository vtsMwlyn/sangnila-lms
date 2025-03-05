<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\SelfAttendance;
use App\Models\CurriculumTopic;
use App\Models\ImportedStudent;
use App\Models\LearningOutcome;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {
	// ===== ADMIN ===== //
	// Showing list of all available courses in Sangnila LMS
	public function admin_index() {
		return view('roles.admin.course.index', [
			'courses' => Course::filter(request(["search"]))->with(['curriculum_topics', 'learning_outcomes', 'teachers', 'students'])->orderByRaw('CASE WHEN status = "active" THEN 0 ELSE 1 END')->orderBy('course_name')->get()
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
			"level" => "required",
			'delivery_mode' => 'required'
		]);

		$validatedData['course_description'] = e($validatedData['course_description']);

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

		$learning_outcomes = $course->learning_outcomes->count();
		$ctopics = $course->curriculum_topics->count();

		$learning_outcomes_empty = $learning_outcomes == 0 ? true : false;
		$syllabus_empty = $ctopics == 0 ? true : false;
		$course_empty = ($ctopics == 0 && $learning_outcomes == 0) ? true : false;
		$no_students_assigned = ($course->students->count() == 0) ? true : false;
		$no_teachers_assigned = ($course->teachers->count() == 0) ? true : false;

		return view('roles.admin.course.show', [
			'course' => $course,
			'learning_outcomes' => LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get(),
			'course_empty' => $course_empty,
			'syllabus_empty' => $syllabus_empty,
			'learning_outcomes_empty' => $learning_outcomes_empty,
			'no_students_assigned' => $no_students_assigned,
			'no_teachers_assigned' => $no_teachers_assigned,
			'all_courses' => Course::where('status', 'active')->orderBy('course_name', 'asc')->get(),
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
			"level" => "required",
			'delivery_mode' => 'required'
		]);

		$validatedData['course_description'] = e($validatedData['course_description']);

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

			$stdatd = StudentAttendance::where("student_id", Auth::user()->id)->get();

			foreach($stdatd as $atd){
				if($atd->attendance->course_id == $c->id && $atd->is_attend == 1){
					$count++;
				}
			}

			if(((($count + 1) % $cs->max_course_session == 0) || $count >= $cs->max_course_session) && $cs->learning_status != 'complete'){
				$shouldPaySoon = true;
			} else {
				$shouldPaySoon = false;
			}

			array_push($paymentReminders, ["course" => $c->course_name, "should_pay_soon" => $shouldPaySoon]);
		}

		// $pushNotif = new PushNotificationController();
		// $pushNotif->sendPushNotification();

		return view('roles.student.course.index', [
			"payment_reminders" => $paymentReminders,
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

		$stdatd = StudentAttendance::where("student_id", Auth::user()->id)->get();

		foreach($stdatd as $atd){
			if($atd->attendance->course_id == $course_id && $atd->is_attend == 1){
				$count++;
			}
		}

		$shouldPaySoon = false;
		$max_session_reached = false;
		if(($count + 1) % $cs->max_course_session == 0){
			$shouldPaySoon = true;
		} else if($count >= $cs->max_course_session && $cs->learning_status == 'learning') {
			$shouldPaySoon = true;
			$max_session_reached = true;
		}

		$progressAndActivity = Progress::where('student_id', $cs->student_id)
			->where('course_id', $cs->course_id)
			->with([
				'activity' => function ($query) {
					$query->select('id', 'session', 'topic_id', 'desc', 'title');
				},
				'activity.topic' => function ($query) {
					$query->select('id', 'title'); // Fetch only necessary columns
				},
				'activity.learning_outcomes' => function ($query) {
					$query->select('learning_outcomes.id', 'number', 'title');
				}
			])
			->join('activities', 'progress.activity_id', '=', 'activities.id')
			->orderBy('activities.session', 'asc')
			->orderBy('activities.created_at', 'asc')
			->select('progress.*') // Select only columns from Progress
			->get()
			->groupBy('activity.session') // Group by activity's session
			->map(function ($progresses, $session) {
				return [
					'session' => $session,
					'progresses' => $progresses->map(function ($progress) {
						return [
							'progress' => $progress,
							'activity' => $progress->activity,
							'topic' => $progress->activity->topic,
							'learning_outcomes' => $progress->activity->learning_outcomes,
						];
					}),
				];
			});

		$todaySelfAttendances = SelfAttendance::where("user_id", Auth::user()->id)->where("course_id", $course_id)->where("self_attendance_date", Carbon::today()->format('Y-m-d'))->get();
		$unfinishedSelfAttendance = $todaySelfAttendances->filter(function($item){
			return $item->check_out_time == null;
		});

		// if($max_session_reached){
		// 	return view('roles.student.course.show', [
		// 		'course' => $cs->course,
		// 		"should_pay_soon" => $shouldPaySoon,
		// 		"max_session_reached" => $max_session_reached
		// 	]);
		// }
		// else {
			return view('roles.student.course.show', [
				'course' => $cs->course,
				'activityProgresses' => $progressAndActivity,
				"should_pay_soon" => $shouldPaySoon,
				"max_session_reached" => $max_session_reached,
				"unfinishedSelfAttendance" => $unfinishedSelfAttendance->first()
			]);
		// }
	}


}
