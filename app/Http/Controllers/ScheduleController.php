<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class ScheduleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		// $schedules = //get all schedule from course_schedule table
		// return view(admin.schedule.index);
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//return view(admin.schedule.create);
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$data = $request->all();
		dd($data);
		// $schedule = new CourseSchedule();
		// $schedule->course_id = $request->course_id;
		// $schedule->day_of_week = $request->day_of_week;
		// $schedule->start_time = $request->start_time;
		// $schedule->end_time = $request->end_time;
		// $schedule->save();
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
		// $schedule = CourseSchedule::findOrFail($schedule_id);
		// dd($schedule);
		// return view(admin.schedule.edit);
		//
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
		$role = Role::where('role_name', 'Student')->get();
		$students = $role->users;
		// $schedule = CourseSchedule::findOrFail($schedule_id);
		// return view(admin.schedule.assign);
	}

	public function assign_store($schedule_id, Request $request){
		$data = $request->all();
		//$student_schedule = new StudentSchedule();
		//$student_schedule->schedule_id = $schedule_id;
		//$student_schedule->student_id = $request->user_id;
		//$student_schedule->save();
		return redirect(route('admin.schedule.show', $schedule_id));
	}
	# SAMPAI SINI
}
