<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MaterialProgress;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class MaterialProgressController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($student_id, $course_id) {
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$existingProgress = MaterialProgress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->pluck('material_id')
			->toArray();

		foreach ($course->materials as $material) {
			if (!in_array($material->id, $existingProgress)) {
				$progress = MaterialProgress::create([
					'student_id' => $student->id,
					'material_id' => $material->id,
					'course_id' => $course->id,
					'status' => 'locked'
				]);
			}
		}

		$newestProgress = MaterialProgress::where('student_id', $student->id)->where('course_id', $course->id)->get();

		return view('roles.teacher.student.progress', [
			'student' => $student,
			'course' => $course,
			'materials' => $newestProgress
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $progress_id) {
		$status = $request->access === 'on' ? 'unlocked' : 'locked';
		$materialProgress = MaterialProgress::findOrFail($progress_id)->first();
		MaterialProgress::findOrFail($progress_id)->update([
			'status' => $status
		]);
		return redirect(route('teacher.student.show.progress', [
			'student_id' => $materialProgress->student_id,
			'course_id' => $materialProgress->course_id
		]));
	}

	public function students_progress($course_id) {
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		return view('roles.teacher.mycourse.progress', [
			'course' => $course,
		]);
	}
}
