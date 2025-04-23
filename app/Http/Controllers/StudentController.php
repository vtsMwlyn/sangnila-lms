<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use App\Models\Progress;
use App\Models\Assessment;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\SelfAttendance;
use App\Models\ImportedStudent;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentController extends Controller {

	// ===== TEACHER ===== //
	// Showing all assigned courses to the teacher to select before continue
	public function teacher_select_course() {
		return  view('roles.teacher.student.select-course', [
			"courses" => auth()->user()->teached_courses
		]);
	}

	// Showing all students in the selected course to select before continue
	public function teacher_select_student($course_id) {
		$course = Course::findOrFail($course_id);
		$grouped_course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->orderByRaw('CASE WHEN learning_status = "learning" THEN 0 WHEN learning_status = "complete" THEN 2 ELSE 3 END')->get()->groupBy('learning_status');

		return view('roles.teacher.student.select-student', [
			'grouped_course_students' => $grouped_course_students,
			"course" => $course
		]);
	}

	// Showing list of student's course activities accessibility status (locked/unlocked) and related learning data of the student (portfolio, meeting link, assessment, etc)
	public function teacher_index($student_id, $course_id) {
		$course = Course::where('status', 'active')->where('id', $course_id)->first();
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();

		// Student's activity access
		$existingProgress = Progress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->pluck('activity_id')
			->toArray();

		$activities = Activity::whereHas('topic', function($query) use ($course){
			return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
		})->orderBy('session', 'asc')->get();

		foreach($activities as $activity) {
			if (!in_array($activity->id, $existingProgress)) {
				Progress::create([
					'student_id' => $student->id,
					'activity_id' => $activity->id,
					'course_id' => $course->id,
					'status' => 'locked'
				]);
			}
		}

		$topics = Topic::where('course_id', $course->id)->where('user_id', Auth::user()->id)->get();
		$newestProgress = Progress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->with('activity') // Load the related activity
			->join('activities', 'progress.activity_id', '=', 'activities.id') // Join with activities
			->orderBy('activities.session', 'asc') // Order by session
			->orderBy('activities.created_at', 'asc') // Order by created_at
			->select('progress.*') // Only select columns from Progress
			->get();

		// Student's assessment
		$assessment = Assessment::where('course_id', $course->id)->where('student_id', $student->id)->where('teacher_id', Auth::user()->id)->first();

		return view('roles.teacher.student.show', [
			'student' => $student,
			'course' => $course,
			"topics" => $topics,
			'newestprogress' => $newestProgress,
			'assessment' => $assessment
		]);
	}

	// Update the activity accessibility in the database
	public function teacher_update_activity_access(Request $request, $course_id, $student_id) {
		$student_progress = Progress::where('student_id', $student_id)
			->where('course_id', $course_id)
			->with('activity') // Load the related activity
			->join('activities', 'progress.activity_id', '=', 'activities.id') // Join with activities
			->orderBy('activities.session', 'asc') // Order by session
			->orderBy('activities.created_at', 'asc') // Order by created_at
			->select('progress.*') // Only select columns from Progress
			->get();

		try {
			DB::beginTransaction();

			foreach($student_progress as $index => $progress){
				$newStatus = ($request->checkbox_value[$index] == 'on') ? "unlocked" : "locked";

				if ($progress->status == "locked" && $newStatus == "unlocked") {
					$updateStatus = $progress->update(["status" => $newStatus]);

					if($updateStatus) {
						Notification::create([
							"user_id" => $student_id,
							"status" => "unread",
							"message" => "New activity \"" . $progress->activity->title . "\" in course " . $progress->course->course_name . " is now accessible!"
						]);
					}
				} else {
					Progress::findOrFail($progress->id)->update(["status" => $newStatus]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to update activity access, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return back()->with("success", "Student's activity access updated successfully!");
	}

	// Update meeting links
	public function teacher_update_meeting_link(Request $request, $course_id, $student_id) {
		$request->validate([
			'meeting_links.*' => 'nullable|url'
		]);

		// return $request->content;

		$student_progress = Progress::where('student_id', $student_id)
			->where('course_id', $course_id)
			->with('activity') // Load the related activity
			->join('activities', 'progress.activity_id', '=', 'activities.id') // Join with activities
			->orderBy('activities.session', 'asc') // Order by session
			->orderBy('activities.created_at', 'asc') // Order by created_at
			->select('progress.*') // Only select columns from Progress
			->get();

		try {
			DB::beginTransaction();

			foreach($student_progress as $index => $progress){
				$updateStatus = $progress->update(["meeting_link" => $request->meeting_links[$index]]);

				// if ($updateStatus > 0) {
				// 	Notification::create([
				// 		"user_id" => $student_id,
				// 		"status" => "unread",
				// 		"message" => "New activity \"" . $progress->activity->title . "\" in course " . $progress->course->course_name . " is now accessible!"
				// 	]);
				// }
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to update meeting links, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return back()->withQuery(['content' => request('content')])->with("successUpdateMeetingLink", "Student's meeting links updated successfully!");
	}

	// ===== ADMIN ===== //
	// Showing list of all active students in Sangnila LMS
	public function admin_index() {
		$students = User::filter(request(["search", "course"]))->where("role_id", 3)->orderBy('full_name', 'asc')->with(['course_students', 'student_attendances'])->get();

		$max_attendances_learning = [];
		$current_attendances_learning = [];
		$percentages_learning = [];
		$studentList_learning = [];

		// To contain prioritized students with current attendance of max attendance - 1
		$max_attendances_prioritized = [];
		$current_attendances_prioritized = [];
		$percentages_prioritized = [];
		$studentList_prioritized = [];

		// To move completed students to the bottom
		$max_attendances_complete = [];
		$current_attendances_complete = [];
		$percentages_complete = [];
		$studentList_complete = [];

		// To move undone students to the bottom
		$max_attendances_undone = [];
		$current_attendances_undone = [];
		$percentages_undone = [];
		$studentList_undone = [];

		// To move unassigned students to the bottom 
		$max_attendances_unassigned = [];
		$current_attendances_unassigned = [];
		$percentages_unassigned = [];
		$studentList_unassigned = [];

		foreach($students as $student){
			$maiscec = []; //max attendances in student current enrolled course
			$caiscec = []; //current attendances in student current enrolled course
			$piscec = []; //percentage in student current enrolled course

			$prioritized = false;
			$completed = 0;

			$learning = false;
			$undone = false;

			$sa = StudentAttendance::where("student_id", $student->id)->with('attendance')->get();

			foreach($student->course_students as $cs){
				$course = $cs->course;

				// Number of attendances (separated for imported students and unimported students)
				if($cs->is_imported){
					$count = ImportedStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->last_attendance_count;
				} else {
					$count = 0;
				}

				foreach($sa as $atd){
					if($atd->attendance->course_id == $course->id /*&& $atd->is_attend == 1*/){
						$count++;
					}
				}

				if(((($count + 1) % $cs->max_course_session == 0) || $count >= $cs->max_course_session) && !in_array($cs->learning_status, ['complete', 'undone'])){
					$prioritized = true;
				}

				switch($cs->learning_status){
					case 'complete': $completed++; break;
					case 'learning': $learning = true; break;
					case 'undone': $undone = true; break;
				}

				array_push($maiscec, $cs->max_course_session);
				array_push($caiscec, $count);
				array_push($piscec, round((float)($count / $cs->max_course_session) * 100));

			}

			// Students reaching max session
			if($prioritized){
				array_push($max_attendances_prioritized, $maiscec);
				array_push($current_attendances_prioritized, $caiscec);
				array_push($percentages_prioritized, $piscec);
				array_push($studentList_prioritized, $student);
			}
			else {
				if($student->status == 'enabled'){
					// Students has no courses assigned at all
					if($student->course_students->count() == 0){
						array_push($max_attendances_unassigned, $maiscec);
						array_push($current_attendances_unassigned, $caiscec);
						array_push($percentages_unassigned, $piscec);
						array_push($studentList_unassigned, $student);
					}

					// Students completed all the assigned courses
					else if($completed == $student->course_students->count()){
						array_push($max_attendances_complete, $maiscec);
						array_push($current_attendances_complete, $caiscec);
						array_push($percentages_complete, $piscec);
						array_push($studentList_complete, $student);
					}

					// Students still learning
					else if($learning) {
						array_push($max_attendances_learning, $maiscec);
						array_push($current_attendances_learning, $caiscec);
						array_push($percentages_learning, $piscec);
						array_push($studentList_learning, $student);
					}

					// Students undone
					else if($undone) {
						array_push($max_attendances_undone, $maiscec);
						array_push($current_attendances_undone, $caiscec);
						array_push($percentages_undone, $piscec);
						array_push($studentList_undone, $student);
					}
				}
			}
		}

		$students = array_merge($studentList_prioritized, $studentList_learning, $studentList_complete, $studentList_undone, $studentList_unassigned);
		$current_attendances = array_merge($current_attendances_prioritized, $current_attendances_learning, $current_attendances_complete, $current_attendances_undone, $current_attendances_unassigned);
		$max_attendances = array_merge($max_attendances_prioritized, $max_attendances_learning, $max_attendances_complete, $max_attendances_undone, $max_attendances_unassigned);
		$percentages = array_merge($percentages_prioritized, $percentages_learning, $percentages_complete, $percentages_undone, $percentages_unassigned);

		// Wrap all data into a single array so we can paginate as one unit
		$combined = [];

		foreach ($students as $index => $student) {
			$combined[] = [
				'student' => $student,
				'current_attendance' => $current_attendances[$index] ?? null,
				'max_attendance' => $max_attendances[$index] ?? null,
				'percentage' => $percentages[$index] ?? null,
			];
		}
		
		// Paginate the combined collection
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$perPage = 30;
		$collection = collect($combined);
		$currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
		
		$paginator = new \Illuminate\Pagination\LengthAwarePaginator(
			$currentPageItems,
			$collection->count(),
			$perPage,
			$currentPage,
			['path' => request()->url(), 'query' => request()->query()]
		);

		// Return view with data
		return view('roles.admin.student.index', [
			'all_students_data' => $paginator,
			// 'students' => $students,
			// "max_attendances" => $max_attendances,
			// "current_attendances" => $current_attendances,
			// "percentages" => $percentages
		]);
	}

	// Shows the details of a student (courses enrolled, data, attendance & assignment summary)
	public function admin_show($student_id) {
		$student = User::findOrFail($student_id);

		//Counting assignments done
		$count_assignment_all = [];
		$count_assignment_col = [];
		$assignment_all = [];
		$assessment_all = [];

		foreach($student->enrolled_courses as $crs){
			$student_assignments = StudentAssignment::where("student_id", $student->id)
				->with('assignment.posted_by', function($query){
					return $query->select('id', 'full_name');
				})
				->with('assignment.submissions', function($query) use ($student_id){
					return $query->select('id', 'student_id', 'assignment_id', 'created_at')->where('student_id', $student_id)->latest();
				})
				->get();

			$student_assignments_in_the_course = [];
			foreach($student_assignments as $asg){
				if($asg->assignment->course_id == $crs->id){
					array_push($student_assignments_in_the_course, $asg);
				}
			}

			$n_asg_subm = 0;
			foreach($student_assignments_in_the_course as $assg){
				foreach($assg->assignment->submissions as $submission){
					if($submission->student_id == $student->id){
						$n_asg_subm++;
						break;
					}
				}
			}

			array_push($count_assignment_all, count($student_assignments_in_the_course));
			array_push($count_assignment_col, $n_asg_subm);

			$assignment_all[$crs->id] = $student_assignments_in_the_course;

			$assessment = Assessment::where('course_id', $crs->id)->where('student_id', $student->id)->first();
			$assessment_all[] = $assessment;
		}

		// return $assessment;

		//Counting attended sessions
		$count_curr_progress = [];
		$count_full_progress = [];
		$attendance_all = [];

		$sa = StudentAttendance::where('student_id', $student->id)
		->join('attendances', 'student_attendances.attendance_id', '=', 'attendances.id')
		->orderBy('attendances.attendance_date', 'asc')->orderBy('student_attendances.start_time') // Sort by attendance_date then start time
		->with([
			'attendance' => function ($query) {
				$query->select('id', 'attendance_date', 'uploader_id', 'course_id');
			},
			'attendance.posted_by' => function ($query) {
				$query->select('id', 'full_name');
			}
		])
		->select('student_attendances.*') // Ensure to select main table fields
		->get();

		foreach($student->enrolled_courses as $course){
			// Number of attendances (separated for imported students and unimported students)
			$cs = CourseStudent::where("course_id", $course->id)->where("student_id", $student->id)->first();

			if($cs->is_imported){
				$count = ImportedStudent::where("course_id", $course->id)->where("student_id", $student->id)->first()->last_attendance_count;
			} else {
				$count = 0;
			}

			$student_attendances_in_the_course = [];
			foreach($sa as $atd){
				if($atd->attendance->course_id == $course->id){
					array_push($student_attendances_in_the_course, $atd);
					$count++;
				}
			}

			array_push($count_full_progress, $cs->max_course_session);
			array_push($count_curr_progress, $count);

			$attendance_all[$course->id] = $student_attendances_in_the_course;
		}

		//Return view with data
		return view('roles.admin.student.show', [
			'student' => $student,
			"full_progress" => $count_full_progress,
			"current_progress" => $count_curr_progress,
			"assignment_if_full" => $count_assignment_all,
			"done_assignment" => $count_assignment_col,
			'assignment_data' => $assignment_all,
			'attendance_data' => $attendance_all,
			'assessment_data' => $assessment_all,
		]);
	}

	// Edit student data page
	public function admin_edit($student_id){
		$student = User::findOrFail($student_id);

		return view("roles.admin.student.edit", [
			"student" => $student,
			"education_levels" => ["Elementary School", "Junior High School", "Senior High School", "College", "Professional"]
		]);
	}

	// Update the student data in the database
	public function admin_update(Request $request, $student_id){
		$student = User::findOrFail($student_id);

		$validationRule = [
			"full_name" => "required|min:3",
			"phone_number" => "nullable",
			"city_of_birth" => "nullable|min:3",
			"date_of_birth" => "nullable",

			"school_name" => "nullable|min:3",
			"student_level" => "nullable",
			"name_parent" => "nullable|min:3",
			"phone_parent" => "nullable"
		];

		$validator = Validator::make($request->all(), $validationRule);

        $validator->sometimes('phone_parent', ['min:9', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'], function ($input) {
            return true;
        });

        $validator->sometimes('phone_number', ['min:9', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'], function ($input) {
            return true;
        });

		$dataToUpdate = $validator->validate();

		try {
			DB::beginTransaction();

			$student->update(["full_name" => $dataToUpdate["full_name"]]);
			unset($dataToUpdate["full_name"]);
			UserDetail::where("user_id", $student->id)->update($dataToUpdate);

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to edit student, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.student.show", $student_id))->with("success", "Successfully updated student data!");
	}

	// ===== STUDENT ===== //
	// Check in
	public function student_check_in($course_id){
		$cs = CourseStudent::where('student_id', Auth::user()->id)->where('course_id', $course_id)->first();

		return view('roles.student.check-in', [
			'course' => $cs->course,
			'teacher' => $cs->teacher
		]);
	}

	// Submit check in data
	public function student_check_in_store(Request $request, $course_id){
		$validatedData = $request->validate([
			'check_in_time' => 'required'
		]);

		try {
			$course = Course::findOrFail($course_id);

			$photoEvidence = '';

			if($request->file('image')){
				$imageFile = $request->file('image');
				$randomName = Str::random(40) . '.webp';
				$relativePath = 'student-checkin/' . $randomName;
				$fullPath = storage_path('app/public/' . $relativePath);
			
				$manager = new ImageManager(new Driver());
				$image = $manager->read($imageFile->getRealPath());
				$image->toWebp(80)->save($fullPath);

				$photoEvidence = $relativePath;
			}
			else {
				return back()->with('danger', 'Check in requires evidence image. Please allow the usage of the camera then try again, or if the problem persists, please kindly contact our IT team.');
			}

			SelfAttendance::create([
				'course_id' => $course->id,
				'user_id' => Auth::user()->id,
				'self_attendance_date' => Carbon::today()->format('Y-m-d'),
				'attendance_evidence' => $photoEvidence,
				'check_in_time' => $validatedData['check_in_time']
			]);
		}
		catch(Exception $e){
			if(isset($validatedData['attendance_evidence'])){
				Storage::disk('public')->delete($validatedData['attendance_evidence']);
			}

			return back()->with('danger', 'Cannot sign in due to system error, please contact our IT team. Error detail: ' . $e->getMessage());
		}

		return redirect(route('student.course.show', $course->id))->with('success', 'Successfully checked in to course ' . $course->course_name . ' at ' . $validatedData['check_in_time'] . ' (GMT+7)');
	}

	// Check out
	public function student_check_out_store($course_id){
		$course = Course::findOrFail($course_id);

		$currentTime = Carbon::now();
		$checkOutTime = Carbon::parse($currentTime)->format('H:i:s');

		$existingStudentAtd = SelfAttendance::where('user_id', Auth::user()->id)->where('course_id', $course->id)->where('self_attendance_date', Carbon::parse($currentTime)->format('Y-m-d'))->get();
		$unfinishedSelfAttendance = $existingStudentAtd->filter(function($item){
			return $item->check_out_time == null;
		})->first();

		if($unfinishedSelfAttendance){
			$unfinishedSelfAttendance->update([
				'check_out_time' => $checkOutTime
			]);
		}
		else {
			return back()->with('danger', 'No attendance data found, probably because you have not checked in yet. If the problem persists please contact our IT team.');
		}

		return back()->with('success', 'Successfully checked out from course ' . $course->course_name . ' at ' . $checkOutTime . ' (GMT+7)');
	}

	// Select a course before continue
	public function student_index(){
		return view('roles.student.learning-documentation.index');
	}

	// Show course documentations
	public function student_show($course_id){
		return view('roles.student.learning-documentation.show', [
			'course' => Course::findOrFail($course_id),
		]);
	}
}
