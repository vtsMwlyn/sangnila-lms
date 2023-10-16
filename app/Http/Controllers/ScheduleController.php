<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Role;
use App\Models\StudentSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$schedules = CourseSchedule::get();
		return view('roles.admin.schedule.index', [
			'schedules' => $schedules
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$courses = Course::get();
		return view('roles.admin.schedule.create', [
			'courses' => $courses
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$courseSchedule = new CourseSchedule;

		$courseSchedule->course_id = $request->course_id;
		$courseSchedule->day_of_week = $request->day_of_week;
		$courseSchedule->start_time = $request->start_time;
		$courseSchedule->end_time = $request->end_time;

		$courseSchedule->save();
		return redirect(route('admin.schedule.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($schhedule_id) {
		// $schedule = CourseSchedule::findOrFail($schedule_id);
		// return view(admin.schedule.show);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($schedule_id) {
		$courses = Course::where('visibility', 'public')->get();
		$schedule = CourseSchedule::findOrFail($schedule_id);
		return view('roles.admin.schedule.edit', [
			'schedule' => $schedule,
			'courses' => $courses
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $schedule_id) {
		$data = $request->except(['_token', '_method']);
		// CourseSchedule::findOrFail($schedule_id)->update($data);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	// student_schedule ===============================

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
		$students = $schedule->course->students;
		return view('roles.admin.schedule.assign', [
			'schedule' => $schedule,
			'students' => $students,
		]);
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
	# SAMPAI SINI
}
