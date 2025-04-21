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
use App\Models\SelfAttendance;
use App\Models\ImportedStudent;
use App\Models\StudentAttendance;
use App\Models\TrialClassAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Google\Service\Classroom\Student;
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

		$remaining_students = [];

		foreach($students as $s){
			$student_is_not_teached = true;
			foreach($all_course_students as $cs){
				if($cs->student->id == $s->id){
					$student_is_not_teached = false;
					break;
				}
			}

			if($student_is_not_teached){
				array_push($remaining_students, $s);
			}
		}

		return view("roles.teacher.attendance.student-select", [
			"course_students" => $all_course_students,
			"remaining_students" => $remaining_students,
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

	// New attendance data input form page
	public function create($course_id){
		$course = Course::findOrFail($course_id);
		$selected_student_ids = session('selected_students', []);
		$students = User::whereIn('id', $selected_student_ids)->with('details')->get();
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->with('activities')->get();
		$allStudents = User::where("role_id", 3)->with('details')->get();

		return view("roles.teacher.attendance.upload", [
			"course" => $course,
			"topics" => $topics,
			"students" => $students/*$filteredUsers*/,
			"allStudents" => $allStudents,
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

				$session_counter = 1;

				foreach($activities as $index => $activity){
					if($activity->session != $session_counter){
						$session_counter++;
					}

					Progress::updateOrCreate([
						"student_id" => $student->id,
						"course_id" => $course->id,
						"activity_id" => $activity->id,
					],
					[
						"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
					]);
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
					'attendance_detail' => $request->attendance_detail[$i],
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

	// ===== STUDENT ====== //
	// Showing all enrolled course to pick before continue
	public function student_index(){
		$cs = CourseStudent::where("student_id", Auth::user()->id)->first();

		return redirect(route("student.attendance.show", $cs->course_id));

		// return view("roles.student.attendance.index", [
		// 	"courseStudents" => CourseStudent::where("student_id", Auth::user()->id)->get()
		// ]);
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
	public function admin_index(){
		$all_lecturer_self_attendances = SelfAttendance::filter(request(['teacher', 'course']))->whereHas('user', function($query){
			return $query->where('role_id', 2);
		})->orderBy('self_attendance_date', 'desc')->orderBy('user_id')->paginate(30)->appends(request()->query());

		$all_student_self_attendances = SelfAttendance::filter(request(['teacher', 'course']))->whereHas('user', function($query){
			return $query->where('role_id', 3);
		})->orderBy('self_attendance_date')->orderBy('user_id')->get();

		$all_student_attendances = StudentAttendance::filter(request(['course', 'student']))->select('student_attendances.*')
			->join('attendances', 'student_attendances.attendance_id', '=', 'attendances.id')
			->orderBy('attendances.attendance_date', 'desc')
			->orderBy('student_attendances.start_time', 'desc')
			->with('attendance')
			->paginate(30)->appends(request()->query());

		$all_trial_class_attendances = TrialClassAttendance::filter(request(['course', 'candidate']))->orderBy('attendance_date', 'desc')->paginate(30)->appends(request()->query());

		return view('roles.admin.attendance.index', [
			'all_lecturer_self_attendances' => $all_lecturer_self_attendances,
			'all_student_self_attendances' => $all_student_self_attendances,
			'all_student_attendances' => $all_student_attendances,
			'all_trial_class_attendances' => $all_trial_class_attendances,
		]);
	}

	// Showing attendance data of a student in all enrolled course
	public function admin_show($student_id, $course_id){
		// Eager load the 'attendance' relationship and order by 'attendance_date'
		$attendances = StudentAttendance::where("student_id", $student_id)
			->whereNot("attendance_detail", "Account disabled")
			->with(['attendance' => function($query) {
				$query->orderBy('attendance_date', 'asc'); // or 'desc' for descending order
			}])
			->get();

		$course = Course::findOrFail($course_id);
		$student = User::findOrFail($student_id);

		// Filter the attendances by the related course_id
		$student_attendances = $attendances->filter(function ($atd) use ($course) {
			return $atd->attendance->course_id == $course->id;
		});

		// Sort the filtered attendances by 'attendance_date'
		$student_attendances = $student_attendances->sortBy(function ($atd) {
			return $atd->attendance->attendance_date;
		});

		// Render the view with the sorted attendances
		return view("roles.admin.student.atd-details", [
			"attendances" => $student_attendances,
			"course" => $course,
			"student" => $student
		]);

	}

	// View all lecturer attendances
	// public function admin_index_lecturer_attendance(){
	// 	$all_lecturer_attendances = SelfAttendance::filter(request(['search']))->whereHas('user', function($query){
	// 		return $query->where('role_id', 2)->orWhere('role_id', 1);
	// 	})->orderBy('self_attendance_date', 'desc')->orderBy('user_id')->get();

	// 	$all_student_attendances = SelfAttendance::filter(request(['search']))->whereHas('user', function($query){
	// 		return $query->where('role_id', 3);
	// 	})->orderBy('self_attendance_date')->orderBy('user_id')->get();

	// 	return view('roles.admin.teacher.attendance-index', [
	// 		'all_lecturer_attendances' => $all_lecturer_attendances,
	// 		'all_student_attendances' => $all_student_attendances
	// 	]);
	// }

	// Delete a lecturer attendance
	public function admin_destroy_lecturer_attendance($self_attendance_id){
		SelfAttendance::findOrFail($self_attendance_id)->delete();

		return back()->with('warning', 'Successfully deleted the lecturer attendance data!');
	}

	// Input student attendance
	public function admin_input($student_id){
		$studentOnly = User::with('enrolled_courses.topics.activities')->findOrFail($student_id);

		return view('roles.admin.student.input-attendance', [
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
			// 'session.*' => 'required',
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
					// 'nth_session' => $request->session[$i],
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

			$session_counter = 1;

			foreach($activities as $index => $activity){
				if($activity->session != $session_counter){
					$session_counter++;
				}

				Progress::updateOrCreate([
					"student_id" => $student->id,
					"course_id" => $course->id,
					"activity_id" => $activity->id,
				],
				[
					"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			return back()->with("danger", "System failed to import old student data, please report the error to our IT team. Error detail: " . $e->getMessage());

			DB::rollback();
		}

		return redirect(route('admin.student.show', $student->id))->with('success', 'Successfully inputed new attendance data for the student!');
	}

	public function admin_edit_student_attendance($student_attendance_id){
		return view('roles.admin.attendance.student-edit', [
			'student_attendance' => StudentAttendance::findOrFail($student_attendance_id)
		]);
	}

	public function admin_update_student_attendance(Request $request, $student_attendance_id){
		$validatedData = $request->validate([
			'attendance_date' => 'required|date',
			'is_attend' => 'required',
			'activity_progress' => 'required',
			'learning_status' => 'required',
			'attendance_detail' => 'required',
			// 'nth_session' => 'required|numeric|min:0',
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

		return redirect(route('admin.student.show', $sa->student->id))->with('success', 'The attendance data has been updated successfully!');
	}

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

			$session_counter = 1;

			foreach($activities as $index => $activity){
				if($activity->session != $session_counter){
					$session_counter++;
				}

				Progress::updateOrCreate([
					"student_id" => $student->id,
					"course_id" => $course->id,
					"activity_id" => $activity->id,
				],
				[
					"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
				]);
			}
		}

		return back()->with('warning', 'Successfully removed the attendance data!');
	}
}
