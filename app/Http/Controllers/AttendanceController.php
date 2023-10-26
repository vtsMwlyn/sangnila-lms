<?php

namespace App\Http\Controllers;

use App\Models\AttendanceByTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller {
	public function index() {
		$courses = Auth::user()->teached_courses;
		return view('roles.teacher.attendance.index', [
			'courses' => $courses
		]);
	}

	public function show($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view('roles.teacher.attendance.show', [
			'course' => $course
		]);
	}

	public function store(Request $request) {
		$course_id = $request->course;
		$teacher_id = Auth::user()->id;

		foreach ($request->input() as $key => $value) {
			if (strpos($key, 'attendance_') !== false) {
				$student_id = str_replace('attendance_', '', $key);

				$status = $value ? 'Attended' : 'Absent';

				// // Debugging output
				// print_r([
				// 	'teacher_id' => $teacher_id,
				// 	'student_id' => $student_id,
				// 	'status' => $status,
				// 	'course_id' => $course_id,
				// ]);

				// Commented out for debugging

				AttendanceByTeacher::create([
					'teacher_id' => $teacher_id,
					'student_id' => $student_id,
					'schedule_id' => $request->schedule,
					'status' => $status,
					'course_id' => $course_id,
				]);

			}
		}

		// Redirect to a success page or flash a success message
		return redirect()->route('teacher.attendance.index');
	}

	public function view($course_id) {
		$course = Auth::user()->teached_courses->where('id', $course_id)->first();
		return view('roles.teacher.attendance.view', [
			'course' => $course
		]);
	}
}
