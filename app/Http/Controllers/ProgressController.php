<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller {
	// ===== TEACHER ===== //
	// Showing list of student's course materials accessibility status (locked/unlocked) and create progress data for the student
	public function index($student_id, $course_id) {
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$existingProgress = Progress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->pluck('material_id')
			->toArray();

		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

		foreach ($topics as $topic) {
			foreach($topic->materials as $material) {
				if (!in_array($material->id, $existingProgress)) {
					$progress = Progress::create([
						'student_id' => $student->id,
						'material_id' => $material->id,
						'course_id' => $course->id,
						'status' => 'locked'
					]);
				}
			}
		}

		$newestProgress = Progress::where('student_id', $student->id)->where('course_id', $course->id)->get();

		return view('roles.teacher.student.progress', [
			'student' => $student,
			'course' => $course,
			"topics" => $topics,
			'newestprogress' => $newestProgress
		]);
	}

	// Update the material accessibility in the database
	public function update(Request $request, $course_id, $student_id) {
		$student_progress = Progress::where("course_id", $course_id)->where("student_id", $student_id)->get();

		foreach($student_progress as $index => $progress){
			$newStatus = ($request->checkbox_value[$index] == 'on')? "unlocked" : "locked";
			Progress::where("id", $progress->id)->update(["status" => $newStatus]);
		}

		return redirect(route('teacher.student.show.progress', [
			'student_id' => $student_id,
			'course_id' => $course_id
		]))->with("successUpdateProgress", "Student's progress updated successfully!");
	}

	public function students_progress($course_id) {
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		return view('roles.teacher.mycourse.progress', [
			'course' => $course,
		]);
	}
}
