<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\Attendance;
use App\Models\AttendanceByTeacher;
use App\Models\MaterialProgress;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class AttendanceController extends Controller {
	// ===== TEACHER ===== //
	// Showing all assigned course to select before continue
	public function index(){
		return view('roles.teacher.attendance.index');
	}

	// Showing all attendance data in the selected course
	public function show($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		$attendances_in_the_course = Attendance::where("course_id", $course->id)->get();

		$attendanceData = [];
		if($attendances_in_the_course->count()){
			$atd_group = [];

			for($i = 0; $i < $attendances_in_the_course->count(); $i++){
				if($i == 0){
					array_push($atd_group, $attendances_in_the_course[0]);

					continue;
				}

				if($attendances_in_the_course[$i]->created_at != $attendances_in_the_course[$i - 1]->created_at){
					array_push($attendanceData, $atd_group);

					$atd_group = [];
				}

				array_push($atd_group, $attendances_in_the_course[$i]);
			}

			array_push($attendanceData, $atd_group);
		}

		return view('roles.teacher.attendance.show', [
			'attendanceData' => $attendanceData,
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	// New attendance data input form page
	public function create($course_id){
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view("roles.teacher.attendance.upload", [
			"course" => $course
		]);
	}

	// Insert new attendance data into database
	public function store(Request $request, $course_id) {
		$validatedData = $request->validate([
			"checkbox_value.*" => "required",
			"attendance_detail.*" => "required|min:3"
		], [
			"attendance_detail.*.required" => "The attendance detail field is required."
		]);

		$course = Course::where("id", $course_id)->first();
		$teacher = Auth::user();

		$i = 0;
		foreach($course->students as $student){
			$attendanceDetail = $validatedData["attendance_detail"][$i];

			if($attendanceDetail == "Account disabled"){
				$isAttend = 2;
			} else {
				$isAttend = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;
			}

			Attendance::create([
				"course_id" => $course->id,
				"teacher_id" => $teacher->id,
				"student_id" => $student->id,
				"is_attend" => $isAttend,
				"attendance_detail" => $attendanceDetail
			]);

			$i++;
		}

		return redirect(route("teacher.attendance.show", $course->id))->with("successUploadAttendance", "Attendance uploaded successfully!");
	}

	// Edit attendance data page
	public function edit($attendance_data_id){
		$Attendance = Attendance::where("id", $attendance_data_id)->first();
		return view("roles.teacher.attendance.edit", [
			"attendanceData" => Attendance::where("created_at", $Attendance->created_at)->get()
		]);
	}

	// Update attendance data in the database
	public function update(Request $request, $attendance_data_id){
		$validatedData = $request->validate([
			"checkbox_value.*" => "required",
			"attendance_detail.*" => "required|min:3"
		], [
			"attendance_detail.*.required" => "The attendance detail field is required."
		]);

		$Attendance = Attendance::where("id", $attendance_data_id)->first();
		$existingAttendanceData = Attendance::where("created_at", $Attendance->created_at)->get();

		$i = 0;
		foreach($existingAttendanceData as $a){
			$isAttend = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;
			$attendanceDetail = $validatedData["attendance_detail"][$i];

			Attendance::where("id", $a->id)->update(["is_attend" => $isAttend, "attendance_detail" => $attendanceDetail]);

			$i++;
		}

		return redirect(route("teacher.attendance.show", $Attendance->course->id))->with("successEditAttendance", "Attendance edited successfully!");
	}

	// ===== STUDENT ====== //
	// Showing all enrolled course to pick before continue
	public function student_index(){
		return view("roles.student.attendance.index", [
			"courseStudents" => CourseStudent::where("user_id", Auth::user()->id)->get()
		]);
	}

	// List of all attendance data in the selected course
	public function student_show($course_id){
		return view("roles.student.attendance.show", [
			"attendances" => Attendance::where("course_id", $course_id)->where("student_id", Auth::user()->id)->whereNot("attendance_detail", "Account disabled")->get(),
			"course" => Course::where("id", $course_id)->first()
		]);
	}


	// ===== ADMIN ===== //
	// Showing attendance data of a student in all enrolled course
	public function admin_show($student_id, $course_id){
		return view("roles.admin.student.atd-details", [
			"attendances" => Attendance::where("course_id", $course_id)->where("student_id", $student_id)->get(),
			"course" => Course::where("id", $course_id)->first(),
			"student" => User::where("id", $student_id)->first()
		]);
	}
}
