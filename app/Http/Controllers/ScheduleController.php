<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Role;
use App\Models\StudentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller {

	// ========== ADMIN ==========

	public function admin_index() {
		$schedules = CourseSchedule::get();
		return view('roles.admin.schedule.index', [
			'schedules' => $schedules
		]);
	}


	public function admin_create() {
		$courses = Course::get();
		return view('roles.admin.schedule.create', [
			'courses' => $courses
		]);
	}


	public function admin_store(Request $request) {
		$courseSchedule = new CourseSchedule;

		$courseSchedule->course_id = $request->course_id;
		$courseSchedule->day_of_week = $request->day_of_week;
		$courseSchedule->start_time = $request->start_time;
		$courseSchedule->end_time = $request->end_time;

		$courseSchedule->save();
		return redirect(route('admin.schedule.index'));
	}


	public function admin_show($schhedule_id) {
		// $schedule = CourseSchedule::findOrFail($schedule_id);
		// return view(admin.schedule.show);
	}


	public function admin_edit($schedule_id) {
		$courses = Course::where('visibility', 'public')->get();
		$schedule = CourseSchedule::findOrFail($schedule_id);
		return view('roles.admin.schedule.edit', [
			'schedule' => $schedule,
			'courses' => $courses
		]);
	}


	public function admin_update(Request $request, $schedule_id) {
		$data = $request->except(['_token', '_method']);
		CourseSchedule::findOrFail($schedule_id)->update($data);
		return redirect(route('admin.schedule.index'));
	}

	public function destroy($id) {
		//
	}

	public function assign_store($schedule_id, Request $request) {
		$data = $request->all();
		StudentSchedule::create([
			'schedule_id' => $schedule_id,
			'student_id' => $request->student
		]);
		return redirect(route('admin.schedule.index'));
		//$student_schedule = new StudentSchedule();
		//$student_schedule->schedule_id = $schedule_id;
		//$student_schedule->student_id = $request->user_id;
		//$student_schedule->save();
		return redirect(route('admin.schedule.show', $schedule_id));
	}

	// student_schedule ===============================

	public function student_index() {
		$schedules = Auth::user()->schedules;
		return view('roles.student.schedule.index', [
			'schedules' => $schedules
		]);
	}

	public function student_show($student_id, $course_id) {
		// $schedules = StudentSchedule::where('studet_id', $student_id)->where('course_id', $course_id)->get();
		// return view(admin.student.show_schedule);
	}

	public function student_edit($student_id, $course_id) {
		// $schedules = StudentSchedule::where('studet_id', $student_id)->where('course_id', $course_id)->first();
		// return view(admin.student.show.schedule);
	}

	public function student_update($student_id, $course_id, Request $request) {
		$data = $request->except(['_token', '_method']);
		// $schedules = StudentSchedule::where('studet_id', $student_id)->where('course_id', $course_id)->update($data);
		// return redirect(route(admin.student.show.schedule));

	}

	// assign schedule to student
	public function assign($schedule_id) {
		$schedule = CourseSchedule::where('id', $schedule_id)->first();

		// Get the student IDs that are already assigned to this schedule
		$assignedStudentIds = $schedule->student_schedules->pluck('student_id');

		// Get students from the course who are not assigned to this schedule
		$students = $schedule->course->students->whereNotIn('id', $assignedStudentIds);
		return view('roles.admin.schedule.assign', [
			'schedule' => $schedule,
			'students' => $students,
		]);
	}

	// ========== Teacher ==========
	public function teacher_index(){
		$courses = Auth::user()->teached_courses;
		return view('roles.teacher.schedule.index', [
			'courses' => $courses
		]);
	}


	# SAMPAI SINI
}
