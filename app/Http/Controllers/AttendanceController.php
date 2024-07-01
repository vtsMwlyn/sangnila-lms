<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\Attendance;
use App\Models\ImportedStudent;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller {
	// ===== TEACHER ===== //
	// Showing all assigned course to select before continue
	public function index(){
		return view('roles.teacher.attendance.index');
	}

	// Showing all attendance data in the selected course
	public function show($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();

		$attendanceData = Attendance::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->latest()->get();

		return view('roles.teacher.attendance.show', [
			'attendanceData' => $attendanceData,
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	// New attendance data input form page
	public function create($course_id){
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

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
			"course_students" => $course_students/*$filteredUsers*/
		]);
	}

	// Insert new attendance data into database
	public function store(Request $request, $course_id) {
		$validatedData = $request->validate([
			"checkbox_value.*" => "required",
			"attendance_detail.*" => "required|min:3",
			"attendance_date" => "required",
			"material_progress.*" => "required",
			"learning_status.*" => "required",
		], [
			"attendance_detail.*.required" => "The attendance detail field is required.",
			"material_progress.*.required" => "The material progress field is required.",
			"learning_status.*.required" => "The learning status field is required."
		]);

		$course = Course::where("id", $course_id)->first();
		$teacher = Auth::user();

		$identifier = $course->id . "_" . $teacher->id . "/" . round(microtime(true) * 1000);

		$newAttendance = Attendance::create([
			"teacher_id" => $teacher->id,
			"course_id" => $course->id,
			"attendance_date" => $validatedData["attendance_date"],
			"attendance_identifier" => $identifier
		]);

		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

		foreach($course_students as $i => $cs){
			$attendanceDetail = $validatedData["attendance_detail"][$i];

			$isAttend = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;

			StudentAttendance::create([
				"user_id" => $cs->student->id,
				"attendance_id" => $newAttendance->id,
				"is_attend" => $isAttend,
				"attendance_detail" => $attendanceDetail,
				"material_progress" => $validatedData["material_progress"][$i],
				"learning_status" => $validatedData["learning_status"][$i]
			]);

			$i++;
		}

		return redirect(route("teacher.attendance.show", $course->id))->with("successUploadAttendance", "Attendance uploaded successfully!");
	}

	// Edit attendance data page
	public function edit($attendance_data_id){
		$attendance = Attendance::where("id", $attendance_data_id)->first();

		return view("roles.teacher.attendance.edit", [
			"attendance" => $attendance,
			"attendanceData" => $attendance->student_attendances,
		]);
	}

	// Update attendance data in the database
	public function update(Request $request, $attendance_data_id){
		// $validatedData = $request->validate([
		// 	"checkbox_value.*" => "required",
		// 	"attendance_detail.*" => "required|min:3",
		// 	"attendance_date" => "required",
		// 	"material_progress.*" => "required",
		// 	"learning_status.*" => "required",
		// ], [
		// 	"attendance_detail.*.required" => "The attendance detail field is required.",
		// 	"material_progress.*.required" => "The material progress field is required.",
		// 	"learning_status.*.required" => "The learning status field is required."
		// ]);

		$attendance = Attendance::where("id", $attendance_data_id)->first();
		// $existingAttendanceData = $attendance->student_attendances;

		$attendance->update(["attendance_date" => $request["attendance_date"]]);

		foreach($attendance->course->students as $i => $student){
			$cs = CourseStudent::where("student_id", $student->id)->where("course_id", $attendance->course_id)->first();

			// Validation
			$invalid = false;
			if(!$request["attendance_detail"][$i] && !$cs->is_imported){
				$invalid = true;
			}

			if($cs->is_imported && $request["checkbox_value"][$i] == "on"){
				if(!$request["material_progress"][$i] || !$request["learning_status"][$i] || !$request["attendance_detail"][$i]){
					$invalid = true;
				}
			}

			if($invalid){
				return back()->with("failedEditAttendance", "Attendance detail, material progress, or learning status of student " . $student->full_name . " is emptied. Make sure you fill all the data (excluding imported student)");
			}

			// Modify data in database
			$isAttend = ($request["checkbox_value"][$i] == "on")? 1 : 0;
			$attendanceDetail = $request["attendance_detail"][$i];

			if(!$cs->is_imported){
				StudentAttendance::where("user_id", $student->id)->where("attendance_id", $attendance->id)->update([
					"is_attend" => $isAttend,
					"attendance_detail" => $attendanceDetail,
					"material_progress" => $request["material_progress"][$i],
					"learning_status" => $request["learning_status"][$i]
				]);
			}
			// else {
			// 	StudentAttendance::create([
			// 		"user_id" => $student->id,
			// 		"attendance_id" => $attendance->id,
			// 		"is_attend" => $isAttend,
			// 		"attendance_detail" => $attendanceDetail,
			// 		"material_progress" => $request["material_progress"][$i],
			// 		"learning_status" => $request["learning_status"][$i]
			// 	]);
			// }
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
		$course = Course::where("id", $course_id)->first();
		$student = User::where("id", $student_id)->first();

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
		$attendances = StudentAttendance::where("user_id", $student_id)->whereNot("attendance_detail", "Account disabled")->get();
		$course = Course::where("id", $course_id)->first();
		$student = User::where("id", $student_id)->first();

		$student_attendances = [];
		foreach($attendances as $atd){
			if($atd->attendance->course_id == $course->id){
				array_push($student_attendances, $atd);
			}
		}

		return view("roles.admin.student.atd-details", [
			"attendances" => $student_attendances,
			"course" => $course,
			"student" => $student
		]);
	}
}
