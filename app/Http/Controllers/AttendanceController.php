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
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller {
	// ===== TEACHER ===== //
	// Showing all assigned course to select before continue
	public function index(){
		return view('roles.teacher.attendance.index');
	}

	// Showing all attendance data in the selected course
	public function show($course_id) {
		$course = Course::findOrFail($course_id);

		$attendanceData = Attendance::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->latest()->get();

		return view('roles.teacher.attendance.show', [
			'attendanceData' => $attendanceData,
			"course" => $course
		]);
	}

	// New attendance data input form page
	public function create($course_id){
		$course = Course::findOrFail($course_id);
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

		// Mechanism to remove student's who reached his/her maximum session and haven't paid yet (if agreed to be implemented)
		// $studentsToRemove = [];

		// foreach($course_students as $cs){
		// 	$sa = StudentAttendance::where("user_id", $cs->student_id)->get();
		// 	if($cs->is_imported){
		// 		$count = ImportedStudent::where("student_id", $cs->student_id)->where("course_id", $course_id)->first()->last_attendance_count;
		// 	} else {
		// 		$count = 0;
		// 	}

		// 	foreach($sa as $atd){
		// 		if($atd->attendance->course_id == $course_id && $atd->is_attend == 1){
		// 			$count++;
		// 		}
		// 	}

		// 	if($cs->max_course_session == $count){
		// 		array_push($studentsToRemove, $cs->student->id);
		// 	}
		// }

		// $filteredUsers = $course_students->reject(function ($courseStudent) use ($studentsToRemove) {
		// 	return in_array($courseStudent->student_id, $studentsToRemove);
		// });

		return view("roles.teacher.attendance.upload", [
			"course" => $course,
			"topics" => $topics,
			"course_students" => $course_students/*$filteredUsers*/
		]);
	}

	// Insert new attendance data into database
	public function store(Request $request, $course_id) {
		$course = Course::findOrFail($course_id);
		$teacher = Auth::user();
		$identifier = $course->id . "_" . $teacher->id . "/" . round(microtime(true) * 1000);

		try {
			DB::beginTransaction();

			$newAttendance = Attendance::create([
				"teacher_id" => $teacher->id,
				"course_id" => $course->id,
				"attendance_date" => $request["attendance_date"],
				"attendance_identifier" => $identifier
			]);

			foreach($request->students as $i => $student_id){
				$cs = CourseStudent::where("student_id", $student_id)->where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->first();
				$attendanceDetail = $request["attendance_detail"][$i];

				$isAttend = ($request["checkbox_value"][$i] == "on")? 1 : 0;

				StudentAttendance::create([
					"user_id" => $cs->student->id,
					"attendance_id" => $newAttendance->id,
					"is_attend" => $isAttend,
					"attendance_detail" => $attendanceDetail,
					"material_progress" => $request["material_progress"][$i],
					"learning_status" => $request["learning_status"][$i]
				]);
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
		]);
	}

	// Update attendance data in the database
	public function update(Request $request, $attendance_data_id){
		$attendance = Attendance::findOrFail($attendance_data_id);

		try {
			DB::beginTransaction();

			$existingAttendanceData = $attendance->student_attendances;

			$attendance->update(["attendance_date" => $request["attendance_date"]]);

			foreach($existingAttendanceData as $existingAtd){
				if(!in_array($existingAtd->student->id, $request->students)){
					StudentAttendance::where("user_id", $existingAtd->student->id)->where("attendance_id", $attendance->id)->delete();
				}
			}

			foreach($request->students as $i => $student_id){
				$cs = CourseStudent::where("student_id", $student_id)->where("course_id", $attendance->course->id)->where("teacher_id", Auth::user()->id)->first();

				// Modify data in database mechanism if the data valid for each students data in the course
				$isAttend = ($request["checkbox_value"][$i] == "on")? 1 : 0;
				$attendanceDetail = $request["attendance_detail"][$i];

				// If the student is not recorded in current attendance data, add them to the list
				if(!$existingAttendanceData->where("user_id", $cs->student->id)->first()){
					StudentAttendance::create([
						"is_attend" => $isAttend,
						"attendance_detail" => $attendanceDetail,
						"material_progress" => $request["material_progress"][$i],
						"learning_status" => $request["learning_status"][$i],
						"user_id" => $student_id,
						"attendance_id" => $attendance->id
					]);
				}
				// If the student is already recorded in current attendance data, update the attendance data
				else {
					StudentAttendance::where("user_id", $cs->student->id)->where("attendance_id", $attendance->id)->update([
						"is_attend" => $isAttend,
						"attendance_detail" => $attendanceDetail,
						"material_progress" => $request["material_progress"][$i],
						"learning_status" => $request["learning_status"][$i]
					]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to create announcement, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.attendance.show", $attendance->course_id))->with("successEditAttendance", "Attendance edited successfully!");
	}

	// ===== STUDENT ====== //
	// Showing all enrolled course to pick before continue
	public function student_index(){
		return view("roles.student.attendance.index", [
			"courseStudents" => CourseStudent::where("student_id", Auth::user()->id)->get()
		]);
	}

	// List of all attendance data in the selected course
	public function student_show($course_id){
		$student_id = Auth::user()->id;
		$attendances = StudentAttendance::where("user_id", $student_id)->whereNot("attendance_detail", "Account disabled")->get();
		$course = Course::findOrFail($course_id);

		$student_attendances = [];
		foreach($attendances as $atd){
			if($atd->attendance->course_id == $course->id){
				array_push($student_attendances, $atd);
			}
		}

		return view("roles.student.attendance.show", [
			"attendances" => $student_attendances,
			"course" => $course
		]);
	}


	// ===== ADMIN ===== //
	// Showing attendance data of a student in all enrolled course
	public function admin_show($student_id, $course_id){
		// Eager load the 'attendance' relationship and order by 'attendance_date'
		$attendances = StudentAttendance::where("user_id", $student_id)
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
}
