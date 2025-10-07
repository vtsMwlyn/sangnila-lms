<?php

namespace App\Http\Controllers;

use PDO;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use App\Models\Progress;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\SelfAttendance;
use App\Models\ImportedStudent;
use App\Models\StudentAttendance;
use App\Models\TrialClassAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Google\Service\CloudTasks\Attempt;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller {
	// ===== HEAD OF LECTURER ===== //
	public function head_of_lecturer_index(){
		return view('roles.head-of-lecturer.attendance.index', [
            'courses' => Course::filter(request(['search']))->orderBy('course_name', 'asc')->orderByRaw('CASE WHEN status = "active" THEN 0 ELSE 1 END')->get()
        ]);
	}

	public function head_of_lecturer_show($course_id){
		$course = Course::findOrFail($course_id);
		$self_attendances = SelfAttendance::filter(request(['search']))->where('course_id', $course->id)->whereHas('user', function($query){
			return $query->where('role_id', 2);
		})->orderBy('self_attendance_date', 'desc')->get();

		return view('roles.head-of-lecturer.attendance.show', [
			'course' => $course,
			'self_attendances' => $self_attendances
		]);
	}

	// ===== TEACHER ===== //
	// Showing all assigned course to select before continue
	public function index(){
		return view('roles.teacher.attendance.index');
	}

	// Showing all attendance data in the selected course
	public function show(Request $request, $course_id) {
		$course = Course::findOrFail($course_id);

		$teached_student_id_list = CourseStudent::where('course_id', $course_id)
			->where('teacher_id', Auth::user()->id)
			->pluck('student_id')
			->toArray();

		$attendanceData = new Attendance();
		if(!$request->show || $request->show == 'my students only'){
			$attendanceData = Attendance::where("course_id", $course->id)
				->with(['student_attendances' => function ($query) use ($teached_student_id_list) {
					$query->whereIn('student_id', $teached_student_id_list)->with('student');
				}])
				->orderBy('attendance_date', 'desc')
				->get();
		}
		else if($request->show == 'all') {
			$attendanceData = Attendance::where("course_id", $course->id)
				->with(['student_attendances' => function ($query) use ($teached_student_id_list) {
					$query->with('student');
				}])
				->orderBy('attendance_date', 'desc')
				->get();
		}
		

		// Remove Attendance records where student_attendances is empty
		$attendanceData = $attendanceData->filter(function ($attendance) {
			return $attendance->student_attendances->isNotEmpty(); // Keep only if it has student_attendances
		})->values(); // Reset array indexes

		$attendanceData2 = new Attendance();
		if(!$request->show || $request->show == 'my students only'){
			$attendanceData2 = Attendance::where("course_id", $course->id)
				->with(['student_attendances' => function ($query) use ($teached_student_id_list) {
					$query->whereIn('student_id', $teached_student_id_list)->with('student');
				}])
				->orderBy('attendance_date', 'asc')
				->get();
		}
		else if($request->show == 'all') {
			$attendanceData2 = Attendance::where("course_id", $course->id)
				->with(['student_attendances' => function ($query) use ($teached_student_id_list) {
					$query->with('student');
				}])
				->orderBy('attendance_date', 'asc')
				->get();
		}
		
		// Remove Attendance records where student_attendances is empty
		$attendanceData2 = $attendanceData2->filter(function ($attendance) {
			return $attendance->student_attendances->isNotEmpty(); // Keep only if it has student_attendances
		})->values(); // Reset array indexes

		$todaySelfAttendances = SelfAttendance::where("user_id", Auth::user()->id)->where("course_id", $course->id)->where("self_attendance_date", Carbon::today()->format('Y-m-d'))->get();
		$unfinishedSelfAttendance = $todaySelfAttendances->filter(function($item){
			return $item->check_out_time == null;
		});

		// Counting total attendances per teached students
		$total_student_attendances = [];
		$courseStudents = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->orderByRaw('CASE WHEN learning_status = "learning" THEN 0 WHEN learning_status = "complete" THEN 1 ELSE 2 END')->get();

		foreach($courseStudents as $cs){
			$sa = StudentAttendance::where('student_id', $cs->student->id)
				->whereHas('attendance', function ($query) use ($course) {
					$query->where('course_id', $course->id);
				})
				->with('attendance')
				->orderByDesc(
					Attendance::select('attendance_date') // Select latest attendance_date
						->whereColumn('attendances.id', 'student_attendances.attendance_id') // Ensure correct join
						->limit(1)
				)
				->get();

			$latest = $sa->first() ?? null;

			if($cs->is_imported){
				$count = ImportedStudent::where("course_id", $course->id)->where("student_id", $cs->student->id)->first()->last_attendance_count;
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
	
			$total_student_attendances[$cs->student->id] = [
				'name' => $cs->student->full_name,
				'status' => $cs->learning_status,
				'curr' => $count,
				'total' => $cs->max_course_session,
				'latest' => $latest
			];
		}

		return view('roles.teacher.attendance.show', [
			'attendanceData' => $attendanceData,
			'attendanceData2' => $attendanceData2,
			"course" => $course,
			"unfinishedSelfAttendance" => $unfinishedSelfAttendance->first(),
			'total_student_attendances' => $total_student_attendances,
		]);
	}

	// Pick students to include in new attendance report
	public function select_students($course_id){
		$course = Course::findOrFail($course_id);

		if($course->topics->count() == 0){
			return back()->with('danger', 'Please fill the topics and activities for this course first! <a href="' . route('teacher.course.show', $course->id) . '" class="font-extrabold underline hover:text-yellow-500">Go to course</a>');
		}

		$students = User::where("role_id", 3)->get();
		$all_course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->orderByRaw('CASE WHEN learning_status = "learning" THEN 0 WHEN learning_status = "complete" THEN 1 ELSE 2 END')->get();

		return view("roles.teacher.attendance.student-select", [
			"course_students" => $all_course_students,
			"course" => $course,
		]);
	}

	public function submit_and_proceed(Request $request, $course_id){
		if(!$request->selected_students){
			return back()->with("danger", "Please select minimum one student!");
		}

		$course = Course::findOrFail($course_id);
		session(["selected_students" => $request->selected_students]);

		return redirect(route("teacher.attendance.upload", $course->id));
	}

	// Substitution attendance list
	public function teacher_substitution_index(){
		$students = User::where('role_id', 3)->whereHas('course_students', function($query){
			return $query->where('learning_status', 'learning');
		})->orderBy('full_name', 'asc')->get();

		$substitution_attendances = Attendance::whereHas('posted_by', function($query){
			return $query->where('id', Auth::id())->orWhereIn('role_id', [1, 6]);
		})->where('is_substitution', 1)->orderBy('attendance_date', 'desc')->get();

		return view('roles.teacher.substitution.index', [
			'substitution_attendances' => $substitution_attendances,
			'students' => $students,
		]);
	}

	// Create new substitution attendance
	public function teacher_substitution_create($student_id){
		$student = User::findOrFail($student_id);

		return view('roles.teacher.substitution.upload', [
			'student' => $student,
			'courses' => Course::where('status', 'active')->orderBy('course_name', 'asc')->with('topics.activities')->get()
		]);
	}

	// Store the substitution attendance
	public function teacher_substitution_store(Request $request, $student_id){
		// return $request;
		$request->validate([
			'course_id.*' => 'required',
			'attendance_date' => 'required',
			'activity_progress.*' => 'required',
			'start_time.*' => 'required',
			'end_time.*' => 'required',
			'learning_status.*' => 'required',
			'attendance_details.*' => 'required',
		]);

		$dataToUpload = $request->only([
			'course_id',
			'attendance_date',
			'start_time',
			'end_time',
			'activity_progress',
			'learning_status',
			'attendance_details'
		]);

		try {
			DB::beginTransaction();

			$student = User::findOrFail($student_id);

			foreach($dataToUpload['course_id'] as $i => $course_id){
				$course = Course::findOrFail($course_id);

				$newAttendance = Attendance::create([
					'course_id' => $course_id,
					'uploader_id' => Auth::id(),
					'attendance_date' => $request->attendance_date,
					'is_substitution' => 1,
				]);

				StudentAttendance::create([
					'attendance_id' => $newAttendance->id,
					'student_id' => $student->id,
					'activity_progress' => $dataToUpload['activity_progress'][$i],
					'learning_status' => $dataToUpload['learning_status'][$i],
					'is_attend' => 1,
					'start_time' => $dataToUpload['start_time'][$i],
					'end_time' => $dataToUpload['end_time'][$i],
					'attendance_detail' => $dataToUpload['attendance_details'][$i],
				]);

				// Auto update progress student
				$activities = Activity::whereHas('topic', function($query) use ($course){
					return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
				})->orderBy('session', 'asc')->get();

				$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
					return $query->where('course_id', $course->id);
				})->get();

				foreach($activities as $activity){
					Progress::updateOrCreate(
						[
							"student_id" => $student->id,
							"course_id" => $course->id,
							"activity_id" => $activity->id,
						],
						[
							"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
						]
					);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			throw $e;
		}

		return redirect(route('teacher.attendance.substitution.index'))->with('success', 'Successfully uploaded substitution attendance report!');
	}

	// Edit substitution attendance data
	public function teacher_substitution_edit($substitution_attendance_id){
		$substitution_attendance = Attendance::findOrFail($substitution_attendance_id);
		$students = User::where('role_id', 3)->whereHas('course_students', function($query){
			return $query->where('learning_status', 'learning');
		})->orderBy('full_name', 'asc')->get();

		return view('roles.teacher.substitution.edit', [
			'substitution_attendance' => $substitution_attendance,
			'courses' => Course::where('status', 'active')->orderBy('course_name', 'asc')->with('topics.activities')->get(),
			'students' => $students,
		]);
	}

	// Save the substitution attendance update
	public function teacher_substitution_update(Request $request, $substitution_attendance_id){
		// return $request;
		$request->validate([
			'student_id' => 'required',
			'course_id' => 'required',
			'attendance_date' => 'required',
			'activity_progress' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'learning_status' => 'required',
			'attendance_details' => 'required',
		]);

		$dataToUpload = $request->only([
			'student_id',
			'course_id',
			'attendance_date',
			'start_time',
			'end_time',
			'activity_progress',
			'learning_status',
			'attendance_details'
		]);

		// return $dataToUpload;

		try {
			DB::beginTransaction();

			$substitution_attendance = Attendance::findOrFail($substitution_attendance_id);
			$course = $substitution_attendance->course;
			$substitution_attendance->student_attendances()->delete();

			$substitution_attendance->update([
				'course_id' => $dataToUpload['course_id'],
				'attendance_date' => $dataToUpload['attendance_date'],
			]);

			$student = User::findOrFail($dataToUpload['student_id']);

			StudentAttendance::create([
				'attendance_id' => $substitution_attendance->id,
				'student_id' => $student->id,
				'activity_progress' => $dataToUpload['activity_progress'],
				'learning_status' => $dataToUpload['learning_status'],
				'is_attend' => 1,
				'start_time' => $dataToUpload['start_time'],
				'end_time' => $dataToUpload['end_time'],
				'attendance_detail' => $dataToUpload['attendance_details'],
			]);

			// Auto update progress student
			$activities = Activity::whereHas('topic', function($query) use ($course){
				return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
			})->orderBy('session', 'asc')->get();

			$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
				return $query->where('course_id', $course->id);
			})->get();

			foreach($activities as $activity){
				Progress::updateOrCreate(
					[
						"student_id" => $student->id,
						"course_id" => $course->id,
						"activity_id" => $activity->id,
					],
					[
						"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
					]
				);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();
		}

		return redirect(route('teacher.attendance.substitution.index'))->with('success', 'Successfully updated the substitution attendance data!');
	}

	// New attendance data input form page
	public function create($course_id){
		$course = Course::findOrFail($course_id);
		$selected_student_ids = session('selected_students', []);
		$students = User::whereIn('id', $selected_student_ids)->with('details')->get();
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->with('activities')->get();
		$allStudentsTeached = User::whereHas('course_students', function($query) use($course){
			return $query->where('course_id', $course->id)->where('teacher_id', Auth::id());
		})->with('details')->get();

		return view("roles.teacher.attendance.upload", [
			"course" => $course,
			"topics" => $topics,
			"students" => $students/*$filteredUsers*/,
			"allStudents" => $allStudentsTeached,
			"exclude_from_dropdown" => $selected_student_ids
		]);
	}

	// Insert new attendance data into database
	public function store(Request $request, $course_id) {
		$request->validate([
			'attendance_date' => 'required|date',
			'is_attend.*' => 'required',
			// 'nth_session.*' => 'required',
			'start_time.*' => 'required',
			'end_time.*' => 'required',
			'activity.*' => 'required',
			'learning_status.*' => 'required',
			'details.*' => 'required',
		]);

		try {
			DB::beginTransaction();

			$course = Course::findOrFail($course_id);
			
			$newAttendance = Attendance::create([
				'attendance_date' => $request->attendance_date,
				'course_id' => $course->id,
				'uploader_id' => Auth::user()->id,
			]);

			foreach($request->is_attend as $studentId => $reqIsAttend){
				$student = User::findOrFail($studentId);

				foreach($reqIsAttend as $i => $isAttend){
					StudentAttendance::create([
						'attendance_id' => $newAttendance->id,
						'student_id' => $student->id,
						'is_attend' => $isAttend == 'on'? 1 : 0,
						// 'nth_session' => $request->nth_session[$studentId][$i],
						'activity_progress' => $request->activity[$studentId][$i],
						'learning_status' => $request->learning_status[$studentId][$i],
						'attendance_detail' => $request->details[$studentId][$i],
						'start_time' => $request->start_time[$studentId][$i],
						'end_time' => $request->end_time[$studentId][$i],
					]);
				}

				// Auto update progress student
				$activities = Activity::whereHas('topic', function($query) use ($course){
					return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
				})->orderBy('session', 'asc')->get();

				$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
					return $query->where('course_id', $course->id);
				})->get();

				foreach($activities as $activity){
					Progress::updateOrCreate(
						[
							"student_id" => $student->id,
							"course_id" => $course->id,
							"activity_id" => $activity->id,
						],
						[
							"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
						]
					);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to upload attendance, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.attendance.show", $course->id))->with("success", "Attendance uploaded successfully!");
	}

	// Edit attendance
	public function edit($id){
		$attendance = Attendance::findOrFail($id);
		$exclude_from_dropdown = $attendance->student_attendances->pluck('student_id')->toArray();
		$course = $attendance->course;
		$allStudentsTeached = User::whereHas('course_students', function($query) use($course){
			return $query->where('course_id', $course->id)->where('teacher_id', Auth::id());
		})->with('details')->get();
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->with('activities')->get();

		$grouped_student_attendances = $attendance->student_attendances->groupBy('student_id');

		return view('roles.teacher.attendance.edit', [
			'course' => $course,
			'attendance' => $attendance,
			'students' => $allStudentsTeached,
			"exclude_from_dropdown" => $exclude_from_dropdown,
			'topics' => $topics,
			'grouped_student_attendances' => $grouped_student_attendances
		]);
	}

	public function update(Request $request, $id){
		$request->validate([
			'attendance_date' => 'required|date',
			'is_attend.*' => 'required',
			'start_time.*' => 'required',
			'end_time.*' => 'required',
			'activity.*' => 'required',
			'learning_status.*' => 'required',
			'details.*' => 'required',
		]);

		try {
			DB::beginTransaction();

			$attendance = Attendance::findOrFail($id);
			$course = $attendance->course;

			$attendance->update([
				'attendance_date' => $request->attendance_date,
			]);

			$attendance->student_attendances()->delete();

			foreach($request->is_attend as $studentId => $reqIsAttend){
				$student = User::findOrFail($studentId);

				foreach($reqIsAttend as $i => $isAttend){
					StudentAttendance::create([
						'attendance_id' => $attendance->id,
						'student_id' => $student->id,
						'is_attend' => $isAttend == 'on'? 1 : 0,
						// 'nth_session' => $request->nth_session[$studentId][$i],
						'activity_progress' => $request->activity[$studentId][$i],
						'learning_status' => $request->learning_status[$studentId][$i],
						'attendance_detail' => $request->details[$studentId][$i],
						'start_time' => $request->start_time[$studentId][$i],
						'end_time' => $request->end_time[$studentId][$i],
					]);
				}

				// Auto update progress student
				$activities = Activity::whereHas('topic', function($query) use ($course){
					return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
				})->orderBy('session', 'asc')->get();

				$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
					return $query->where('course_id', $course->id);
				})->get();

				foreach($activities as $activity){
					Progress::updateOrCreate(
						[
							"student_id" => $student->id,
							"course_id" => $course->id,
							"activity_id" => $activity->id,
						],
						[
							"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
						]
					);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			throw $e;
		}

		return redirect(route("teacher.attendance.show", $course->id))->with("success", "Attendance edited successfully!");
	}

	// Upload trial class attendance
	public function teacher_create_trial_class_attendance($course_id){
		return view('roles.teacher.attendance.upload-trial-class', [
			'course' => Course::findOrFail($course_id)
		]);
	}

	// Store trial class attendance
	public function teacher_store_trial_class_attendance(Request $request, $course_id){
		$course = Course::findOrFail($course_id);

		$request->validate([
			'attendance_date.*' => 'required',
			'candidate_name.*' => 'required',
			'start_time.*' => 'required',
			'end_time.*' => 'required',
			'attendance_detail.*' => 'required',
		]);

		try {
			DB::beginTransaction();

			foreach($request->candidate_name as $i => $candidate_name){
				TrialClassAttendance::create([
					'course_id' => $course->id,
					'uploader_id' => Auth::user()->id,
					'attendance_date' => $request->attendance_date[$i],
					'candidate_name' => $candidate_name,
					'start_time' => $request->start_time[$i],
					'end_time' => $request->end_time[$i],
					'attendance_detail' => e($request->attendance_detail[$i]),
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with('danger', 'System failed to store the trial class attendances data. Please contact our IT team to fix this issue. Error detail: ' . $e->getMessage());
		}

		return redirect(route('teacher.attendance.show', ['course_id' => $course_id, 'content' => 'trial class']))->with('success', 'Successfully added the trial class attendance data!');
	}

	// Edit trial class attendance
	public function teacher_edit_trial_class_attendance($course_id, $trial_class_attendance_id){
		$trial_class_attendance = TrialClassAttendance::findOrFail($trial_class_attendance_id);

		return view('roles.teacher.attendance.edit-trial-class', [
			'course' => $trial_class_attendance->course,
			'trial_class_attendance' => $trial_class_attendance
		]);
	}

	// Update trial class attendance
	public function teacher_update_trial_class_attendance(Request $request, $course_id, $trial_class_attendance_id){
		$validatedData = $request->validate([
			'candidate_name' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'attendance_date' => 'required|date',
			'attendance_detail' => 'required'
		]);

		$validatedData['attendance_detail'] = e($validatedData['attendance_detail']);

		TrialClassAttendance::findOrFail($trial_class_attendance_id)->update($validatedData);

		return redirect(route('teacher.attendance.show', ['course_id' => $course_id, 'content' => 'trial class']))->with('success', 'Successfully edited the trial class attendance!');
	}

	// ===== STUDENT ====== //
	// Showing all enrolled course to pick before continue
	public function student_index(){
		$cs = CourseStudent::where("student_id", Auth::user()->id)->first();

		return redirect(route("student.attendance.show", $cs->course_id));
	}

	// List of all attendance data in the selected course
	public function student_show($course_id){
		$student_id = Auth::user()->id;
		$attendances = StudentAttendance::where("student_id", $student_id)->whereNot("attendance_detail", "Account disabled")->get();
		$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $course_id)->first();

		$student_attendances = [];
		$n_attend = 0;
		foreach($attendances as $atd){
			if($atd->attendance->course_id == $cs->course_id){
				array_push($student_attendances, $atd);
				if($atd->is_attend == 1){
					$n_attend++;
				}
			}
		}

		return view("roles.student.attendance.show", [
			"attendances" => $student_attendances,
			"course_student" => $cs,
			"n_attend" => $n_attend
		]);
	}


	// ===== ADMIN ===== //
	// List all attendances from lecturer and students
	public function admin_index(){
		$all_lecturer_self_attendances = SelfAttendance::filter(request(['teacher', 'course']))->whereHas('user', function($query){
			return $query->where('role_id', 2);
		})->orderBy('self_attendance_date', 'desc')->orderBy('user_id')->paginate(30)->appends(request()->query());

		$all_student_self_attendances = SelfAttendance::filter(request(['teacher', 'course']))->whereHas('user', function($query){
			return $query->where('role_id', 3);
		})->orderBy('self_attendance_date')->orderBy('user_id')->get();

		$all_student_attendances = StudentAttendance::filter(request(['course', 'student', 'uploader']))
			->select('student_attendances.*')
			->join('attendances', 'student_attendances.attendance_id', '=', 'attendances.id')
			->orderBy('attendances.attendance_date', 'desc')
			->orderBy('student_attendances.start_time', 'desc')
			->with(['attendance', 'student']) // make sure student is loaded
			->paginate(30)
			->appends(request()->query());

		$all_student_attendances_asc = StudentAttendance::filter(request(['course', 'student']))
			->select('student_attendances.*')
			->join('attendances', 'student_attendances.attendance_id', '=', 'attendances.id')
			->orderBy('attendances.attendance_date', 'asc')
			->orderBy('student_attendances.start_time', 'asc')
			->with(['attendance', 'student'])
			->get();

		$studentCourseCounts = [];
		$sessionCounter = [];

		foreach ($all_student_attendances_asc as $sa) {
			$studentId = strval($sa->student_id);
			$courseId = strval($sa->attendance->course_id);

			// Initialize if not set, then increment
			if (!isset($studentCourseCounts[$studentId][$courseId])) {
				$studentCourseCounts[$studentId][$courseId] = 0;
			}

			$studentCourseCounts[$studentId][$courseId]++;
			$sessionCounter[strval($sa->id)] = $studentCourseCounts[$studentId][$courseId];
		}


		$all_trial_class_attendances = TrialClassAttendance::filter(request(['course', 'candidate']))->orderBy('attendance_date', 'desc')->paginate(30)->appends(request()->query());

		return view('roles.admin.attendance.index', [
			'all_lecturer_self_attendances' => $all_lecturer_self_attendances,
			'all_student_self_attendances' => $all_student_self_attendances,
			'all_student_attendances' => $all_student_attendances,
			'all_trial_class_attendances' => $all_trial_class_attendances,
			'session_counter' => $sessionCounter
		]);
	}

	// Showing attendance data of a student in all enrolled course
	public function admin_show($student_id, $course_id){
		$attendances = StudentAttendance::where("student_id", $student_id)
			->whereNot("attendance_detail", "Account disabled")
			->with(['attendance' => function($query) {
				$query->orderBy('attendance_date', 'asc');
			}])
			->get();

		$course = Course::findOrFail($course_id);
		$student = User::findOrFail($student_id);

		$student_attendances = $attendances->filter(function ($atd) use ($course) {
			return $atd->attendance->course_id == $course->id;
		});

		$student_attendances = $student_attendances->sortBy(function ($atd) {
			return $atd->attendance->attendance_date;
		});

		return view("roles.admin.student.atd-details", [
			"attendances" => $student_attendances,
			"course" => $course,
			"student" => $student
		]);
	}

	// Delete a lecturer attendance
	public function admin_destroy_lecturer_attendance($self_attendance_id){
		SelfAttendance::findOrFail($self_attendance_id)->delete();

		return back()->with('warning', 'Successfully deleted the lecturer attendance data!');
	}

	// Input student attendance
	public function admin_input($student_id){
		$studentOnly = User::with('enrolled_courses.topics.activities')->findOrFail($student_id);

		return view('roles.admin.attendance.student-create', [
			'student' => $studentOnly,
			'allCoursesWithTopicsAndActivities' => $studentOnly->enrolled_courses,
		]);
	}

	// Retrieve data and store student attendance by admin
	public function admin_store(Request $request, $student_id){
		$request->validate([
			'course_id.*' => 'required',
			'attendance_date.*' => 'required',
			'is_attended.*' => 'required',
			'activity_progress.*' => 'required',
			'start_time.*' => 'required',
			'end_time.*' => 'required',
			'learning_status.*' => 'required',
			'attendance_details.*' => 'required',
		]);

		try {
			DB::beginTransaction();

			$student = User::findOrFail($student_id);

			foreach($request->is_attended as $i => $isAttended){
				$course = Course::findOrFail($request->course_id[$i]);

				$existingAttendance = Attendance::where('attendance_date', $request->attendance_date[$i])->where('course_id', $course->id)->where('uploader_id', Auth::user()->id)->first();

				$attendanceId = 0;

				if($existingAttendance){
					$attendanceId = $existingAttendance->id;
				}
				else {
					$newAttendance = Attendance::create([
						'uploader_id' => Auth::user()->id,
						'course_id' => $request->course_id[$i],
						'attendance_date' => $request->attendance_date[$i],
					]);

					$attendanceId = $newAttendance->id;
				}

				StudentAttendance::create([
					'attendance_id' => $attendanceId,
					'start_time' => $request->start_time[$i],
					'end_time' => $request->end_time[$i],
					'student_id' => $student->id,
					'is_attend' => $isAttended,
					'attendance_detail' => $request->attendance_details[$i],
					'activity_progress' => $request->activity_progress[$i],
					'learning_status' => $request->learning_status[$i],
				]);
			}

			$teacher = CourseStudent::where('course_id', $course->id)->where('student_id', $student->id)->first()->teacher;

			// Auto update progress student
			$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
				return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
			})->orderBy('session', 'asc')->get();

			$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
				return $query->where('course_id', $course->id);
			})->get();

			foreach($activities as $activity){
				Progress::updateOrCreate(
					[
						"student_id" => $student->id,
						"course_id" => $course->id,
						"activity_id" => $activity->id,
					],
					[
						"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
					]
				);
			}

			DB::commit();
		}
		catch(Exception $e){
			return back()->with("danger", "System failed to import old student data, please report the error to our IT team. Error detail: " . $e->getMessage());

			DB::rollback();
		}

		return redirect(route('admin.attendance.index', ['content' => 'student']))->with('success', 'Successfully inputed new attendance data for the student!');
	}

	// Edit a student attendance data
	public function admin_edit_student_attendance($student_attendance_id){
		return view('roles.admin.attendance.student-edit', [
			'student_attendance' => StudentAttendance::findOrFail($student_attendance_id)
		]);
	}

	// Save the student attendance data
	public function admin_update_student_attendance(Request $request, $student_attendance_id){
		$validatedData = $request->validate([
			'attendance_date' => 'required|date',
			'is_attend' => 'required',
			'activity_progress' => 'required',
			'learning_status' => 'required',
			'attendance_detail' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
		]);

		if($validatedData['is_attend'] == 0){
			$validatedData['activity_progress'] = 'Absent';
			$validatedData['learning_status'] = 'Absent';
			$validatedData['start_time'] = '00:00';
			$validatedData['end_time'] = '00:00';
		}

		$sa = StudentAttendance::findOrFail($student_attendance_id);
		$sa->attendance->update(['attendance_date' => $validatedData['attendance_date']]);
		$sa->update($validatedData);

		return redirect(route('admin.attendance.index', ['content' => 'student']))->with('success', 'The attendance data has been updated successfully!');
	}

	// Delete a student attendance data
	public function admin_destroy_student_attendance($student_attendance_id){
		$studentAttendance = StudentAttendance::findOrFail($student_attendance_id);

		$attendance = $studentAttendance->attendance;
		$student = $studentAttendance->student;
		$course = $attendance->course;
		$cs = CourseStudent::where('course_id', $course->id)->where('student_id', $student->id)->first();

		$teacher = null;

		if($cs){
			$teacher = $cs->teacher;
		}
		

		if($attendance->student_attendances->count() == 1){
			$studentAttendance->delete();
			$attendance->delete();
		}
		else {
			$studentAttendance->delete();
		}

		if($teacher){
			// Auto update progress student
			$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
				return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
			})->orderBy('session', 'asc')->get();

			$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
				return $query->where('course_id', $course->id);
			})->get();

			foreach($activities as $activity){
				Progress::updateOrCreate(
					[
						"student_id" => $student->id,
						"course_id" => $course->id,
						"activity_id" => $activity->id,
					],
					[
						"status" => $activity->session <= $student_attendances->count() + 1 ? 'unlocked' : 'locked',
					]
				);
			}
		}

		return back()->with('warning', 'Successfully removed the attendance data!');
	}

	// Edit a trial class attendance
	public function admin_edit_trial_class_attendance($trial_class_attendance_id){
		return view('roles.admin.attendance.trial-class-edit', [
			'trial_class_attendance' => TrialClassAttendance::findOrFail($trial_class_attendance_id),
		]);
	}

	// Save the trial class attendance
	public function admin_update_trial_class_attendance(Request $request, $trial_class_attendance_id){
		$validatedData = $request->validate([
			'attendance_date' => 'required',
			'candidate_name' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'attendance_detail' => 'required',
		]);

		$validatedData['attendance_detail'] = e($validatedData['attendance_detail']);

		TrialClassAttendance::findOrFail($trial_class_attendance_id)->update($validatedData);

		return redirect(route('admin.attendance.index', ['content' => 'trial class']))->with('success', 'Successfully edited the trial class attendance data!');
	}

	// Delete a trial class attendance
	public function admin_destroy_trial_class_attendance($trial_class_attendance_id){
		TrialClassAttendance::findOrFail($trial_class_attendance_id)->delete();

		return back()->with('warning', 'Successfully deleted the trial class attendance data!');
	}
}
