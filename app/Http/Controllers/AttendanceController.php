<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\SelfAttendance;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Google\Service\Classroom\Student;
use Google\Service\CloudTasks\Attempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDO;

class AttendanceController extends Controller {
	// ===== TEACHER ===== //
	// Showing all assigned course to select before continue
	public function index(){
		return view('roles.teacher.attendance.index');
	}

	// Showing all attendance data in the selected course
	public function show($course_id) {
		$course = Course::findOrFail($course_id);

		$attendanceData = Attendance::where("course_id", $course->id)->whereHas('posted_by', function($query){
			return $query->where('id', Auth::user()->id)->orWhere('role_id', 1);
		})->with(['student_attendances.student'])->orderBy('attendance_date', 'desc')->get();

		$attendanceData2 = Attendance::where("course_id", $course->id)->whereHas('posted_by', function($query){
			return $query->where('id', Auth::user()->id)->orWhere('role_id', 1);
		})->with(['student_attendances.student'])->orderBy('attendance_date', 'asc')->get();

		$todaySelfAttendances = SelfAttendance::where("user_id", Auth::user()->id)->where("course_id", $course->id)->where("self_attendance_date", Carbon::today()->format('Y-m-d'))->get();
		$unfinishedSelfAttendance = $todaySelfAttendances->filter(function($item){
			return $item->check_out_time == null;
		});

		return view('roles.teacher.attendance.show', [
			'attendanceData' => $attendanceData,
			'attendanceData2' => $attendanceData2,
			"course" => $course,
			"unfinishedSelfAttendance" => $unfinishedSelfAttendance->first()
		]);
	}

	// Pick students to include in new attendance report
	public function select_students($course_id){
		$course = Course::findOrFail($course_id);

		if($course->topics->count() == 0){
			return back()->with('courseHasNoTopicsAndActivities', 'Please fill the topics and activities for this course first! <a href="' . route('teacher.mycourse.show', $course->id) . '" class="font-extrabold underline hover:text-yellow-500">Go to course</a>');
		}

		$students = User::where("role_id", 3)->get();
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

		$remaining_students = [];

		foreach($students as $s){
			$student_is_not_teached = true;
			foreach($course_students as $cs){
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
			"course_students" => $course_students,
			"remaining_students" => $remaining_students,
			"course" => $course,
		]);
	}

	public function submit_and_proceed(Request $request, $course_id){
		if(!$request->selected_students){
			return back()->with("failProceed", "Please select minimum one student!");
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
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

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
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to upload attendance, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.attendance.show", $course->id))->with("successUploadAttendance", "Attendance uploaded successfully!");
	}

	// Edit attendance data page
	public function edit($attendance_data_id){
		$attendance = Attendance::findOrFail($attendance_data_id);

		return view("roles.teacher.attendance.edit", [
			"attendance" => $attendance,
			"attendanceData" => $attendance->student_attendances,
			"all_students" => User::where("role_id", 3)->get()
		]);
	}

	// Update attendance data in the database
	public function update(Request $request, $attendance_data_id){
		$attendance = Attendance::findOrFail($attendance_data_id);

		$validator = Validator::make($request->all(), [
			"attendance_date" => "required",
			"attendance_detail.*" => "required|min:3",
			"students.*" => "required"
		]);

		// Collect initial validation errors into the $errors array
		$errors = [];
		$validatedData = [];

		if ($validator->fails()) {
			$errors = $validator->errors()->toArray(); // Collect errors from the initial validation
		}

		foreach($request->students as $i => $student_id){
			$isAttend = ($request["checkbox_value"][$i] == "on")? 1 : 0;

			// Validate conditional fields based on attendance status
			$attendanceData = [
				'attendance_detail' => $request->input("attendance_detail.$i"),
				'activity_progress' => $isAttend ? $request->input("activity_progress.$i") : null,
				'learning_status' => $isAttend ? $request->input("learning_status.$i") : null,
				'other_activity' => $isAttend ? $request->input("other_activity.$i") : null,
				'attended' => $isAttend,
			];

			// If activity_progress is filled, ensure learning_status is provided
			if ($isAttend == 1 && $attendanceData['activity_progress'] && !$attendanceData['learning_status']) {
				$errors["learning_status.$i"] = "Learning status is required if activity progress is provided.";
			}
			else if ($isAttend == 1 && !$attendanceData['activity_progress'] && $attendanceData['learning_status']) {
				$errors["activity_progress.$i"] = "Activity progress is required if learning status is provided.";
			}

			if($isAttend == 1 && $attendanceData['activity_progress'] == "other" && !$attendanceData['other_activity']){
				$errors["other_activity.$i"] = "Please specified the activity.";
			}

			// Collect valid data for the second loop if no errors
			if (empty($errors)) {
				$validatedData[] = [
					"student_id" => $student_id,
					"attendance_data" => $attendanceData
				];
			}
		}

		// If errors exist (from either the initial validation or the loop), return them
		if (!empty($errors)) {
			return back()->withErrors($errors)->withInput();
		}

		try {
			DB::beginTransaction();

			$existingAttendanceData = $attendance->student_attendances;

			$attendance->update(["attendance_date" => $request["attendance_date"]]);

			foreach($existingAttendanceData as $ead){
				StudentAttendance::find($ead->id)->delete();
			}

			foreach($validatedData as $vd){
				StudentAttendance::create([
					"is_attend" => $vd["attendance_data"]["attended"],
					"attendance_detail" => $vd["attendance_data"]["attendance_detail"],
					"activity_progress" => ($vd["attendance_data"]["activity_progress"] == "other")? $vd["attendance_data"]["other_activity"] : $vd['attendance_data']['activity_progress'],
					"learning_status" => $vd["attendance_data"]["learning_status"],
					"is_custom" => ($vd["attendance_data"]["activity_progress"] == "other")? 1 : 0,
					"student_id" => intval($vd["student_id"]),
					"attendance_id" => $attendance->id
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to update attendance, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.attendance.show", $attendance->course_id))->with("successEditAttendance", "Attendance edited successfully!");
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
	public function admin_index_lecturer_attendance(){
		$all_lecturer_attendances = SelfAttendance::filter(request(['search']))->whereHas('user', function($query){
			return $query->where('role_id', 2)->orWhere('role_id', 1);
		})->orderBy('self_attendance_date')->orderBy('user_id')->get();

		$all_student_attendances = SelfAttendance::filter(request(['search']))->whereHas('user', function($query){
			return $query->where('role_id', 3);
		})->orderBy('self_attendance_date')->orderBy('user_id')->get();

		return view('roles.admin.teacher.attendance-index', [
			'all_lecturer_attendances' => $all_lecturer_attendances,
			'all_student_attendances' => $all_student_attendances
		]);
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
			$course = Course::findOrFail($request->course_id);

			foreach($request->is_attended as $i => $isAttended){
				$newAttendance = Attendance::create([
					'uploader_id' => Auth::user()->id,
					'course_id' => $request->course_id[$i],
					'attendance_date' => $request->attendance_date[$i],
				]);

				StudentAttendance::create([
					'attendance_id' => $newAttendance->id,
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

			DB::commit();
		}
		catch(Exception $e){
			throw $e;

			DB::rollback();
		}

		return redirect(route('admin.student.show', $student->id))->with('successInputAttendance', 'Successfully inputed new attendance data for the student!');
	}

	public function admin_edit_student_attendance($student_attendance_id){
		return view('roles.admin.student.edit-attendance', [
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

		return redirect(route('admin.student.show', $sa->student->id))->with('successEditAttendance', 'The attendance data has been updated successfully!');
	}

	public function admin_destroy_student_attendance($student_attendance_id){
		$studentAttendance = StudentAttendance::findOrFail($student_attendance_id);

		$attendance = $studentAttendance->attendance;

		if($attendance->student_attendances->count() == 1){
			$studentAttendance->delete();
			$attendance->delete();
		}
		else {
			$studentAttendance->delete();
		}

		return back()->with('successDeleteStudentAttendance', 'Successfully removed the attendance data!');
	}
}
