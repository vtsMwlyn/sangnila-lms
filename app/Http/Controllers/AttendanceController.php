<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\StudentAttendance;
use App\Models\AttendanceByTeacher;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class AttendanceController extends Controller {

	public function index(){
		return view('roles.teacher.attendance.index');
	}

	public function show($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view('roles.teacher.attendance.show', [
			'attendanceData' => StudentAttendance::where("course_id", $course->id)->latest()->paginate(3 * $course->students->count()),
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	public function create($course_id){
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view("roles.teacher.attendance.upload", [
			"course" => $course
		]);
	}

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
			$isAttend = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;
			$attendanceDetail = $validatedData["attendance_detail"][$i];

			StudentAttendance::create([
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

	public function view($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view('roles.teacher.attendance.view', [
			'course' => $course
		]);
	}

	public function edit($attendance_data_id){
		$studentAttendance = StudentAttendance::where("id", $attendance_data_id)->first();
		return view("roles.teacher.attendance.edit", [
			"attendanceData" => StudentAttendance::where("created_at", $studentAttendance->created_at)->get()
		]);
	}

	public function update(Request $request, $attendance_data_id){
		$validatedData = $request->validate([
			"checkbox_value.*" => "required",
			"attendance_detail.*" => "required|min:3"
		], [
			"attendance_detail.*.required" => "The attendance detail field is required."
		]);

		$studentAttendance = StudentAttendance::where("id", $attendance_data_id)->first();
		$existingAttendanceData = StudentAttendance::where("created_at", $studentAttendance->created_at)->get();

		$i = 0;
		foreach($existingAttendanceData as $a){
			$isAttend = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;
			$attendanceDetail = $validatedData["attendance_detail"][$i];

			StudentAttendance::where("id", $a->id)->update(["is_attend" => $isAttend, "attendance_detail" => $attendanceDetail]);

			$i++;
		}

		return redirect(route("teacher.attendance.show", $studentAttendance->course->id))->with("successEditAttendance", "Attendance edited successfully!");
	}

	// Student only
	public function student_index(){
		return view("roles.student.attendance.index", [
			"courses" => CourseStudent::where("user_id", Auth::user()->id)->get()
		]);
	}

	public function student_show($course_id){
		return StudentAttendance::where("course_id", $course_id)->where("student_id", Auth::user()->id)->get();
		return view("roles.student.attendance.show", [
			"attendances" => StudentAttendance::where("course_id", $course_id)->where("student_id", Auth::user()->id)->get()
		]);
	}
}
