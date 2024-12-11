<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller {
	// ===== TEACHER ===== //
	// Showing list of student's course activities accessibility status (locked/unlocked) and create progress data for the student
	public function index($student_id, $course_id) {
		$course = Course::where('status', 'active')->where('id', $course_id)->first();
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$existingProgress = Progress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->pluck('activity_id')
			->toArray();

		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

		foreach ($topics as $topic) {
			foreach($topic->activities as $activity) {
				if (!in_array($activity->id, $existingProgress)) {
					$progress = Progress::create([
						'student_id' => $student->id,
						'activity_id' => $activity->id,
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

	// Update the activity accessibility in the database
	public function update(Request $request, $course_id, $student_id) {
		$student_progress = Progress::where("course_id", $course_id)->where("student_id", $student_id)->get();

		try {
			DB::beginTransaction();

			foreach($student_progress as $index => $progress){
				$newStatus = ($request->checkbox_value[$index] == 'on') ? "unlocked" : "locked";

				if ($progress->status == "locked" && $newStatus == "unlocked") {
					$updateStatus = Progress::findOrFail($progress->id)->update(["status" => $newStatus]);

					if ($updateStatus > 0) {
						Notification::create([
							"user_id" => $student_id,
							"status" => "unread",
							"message" => "New activity \"" . $progress->activity->title . "\" in course " . $progress->course->course_name . " is now accessible!"
						]);
					}
				} else {
					Progress::findOrFail($progress->id)->update(["status" => $newStatus]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to update activity access, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.student.show.progress', ['student_id' => $student_id, 'course_id' => $course_id]))->with("successUpdateProgress", "Student's progress updated successfully!");
	}

	public function students_progress($course_id) {
		$course = Course::where('status', 'active')->where('id', $course_id)->first();
		return view('roles.teacher.mycourse.progress', [
			'course' => $course,
		]);
	}
}
