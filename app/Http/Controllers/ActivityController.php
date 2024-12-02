<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\Progress;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller {
	// ===== TEACHER ===== //
	// New activity input page
	public function teacher_create($topic_id) {
		$topic = Topic::findOrFail($topic_id);
		return view('roles.teacher.topic-and-activity.create-activity', [
			'topic' => $topic
		]);
	}

	// Insert the new activity data into database
	public function teacher_store(Request $request, $topic_id) {
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"link" => "nullable|url",
			"desc" => "required|min:3"
		]);

		$topic = Topic::findOrFail($topic_id);

		try {
			Activity::create([
				"topic_id" => $topic->id,
				"title" => $validatedData["title"],
				"link" => $validatedData["link"],
				"desc" => $validatedData["desc"]
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.mycourse.topic.show', [$topic->course->id, $topic->id]))->with("successUploadActivity", "Successfully uploaded new activity to the topic!");
	}

	// Edit activity input page
	public function teacher_edit($activity_id) {
		return view('roles.teacher.topic-and-activity.edit-activity', [
			'activity' => Activity::findOrFail($activity_id)
		]);
	}

	// Update the activity data in the database
	public function teacher_update(Request $request, $activity_id) {
		$data = $request->validate([
			"title" => "required|min:3",
			"link" => "nullable|url",
			"desc" => "required|min:3"
		]);

		$activity = Activity::findOrFail($activity_id);

		try {
			$activity->update($data);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('teacher.mycourse.topic.show', [$activity->topic->course->id, $activity->topic->id]))->with("successEditActivity", "Successfully update activity data!");
	}

	// Activity deletion confirmation
	public function teacher_delete($activity_id) {
		return view("roles.teacher.topic-and-activity.delete-activity-confirmation", [
			"activity" => Activity::findOrFail($activity_id)
		]);
	}

	// Remove activity data from database
	public function teacher_destroy($activity_id) {
		$activity = Activity::findOrFail($activity_id);
		$cid = $activity->topic->course->id;
		$tid = $activity->topic->id;

		$activity->delete();

		return redirect(route("teacher.mycourse.topic.show", [$cid, $tid]))->with("successDeleteActivity", "Successfully deleted activity from the topic!");
	}

	// ===== STUDENT ===== //
	public function preview($activity_id){
		$activity = Activity::findOrFail($activity_id);

		// Check if the user reaches maximum course session
		$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $activity->topic->course_id)->first();

		if($cs->is_imported){
			$count = ImportedStudent::where("course_id", $activity->topic->course_id)->where("student_id", Auth::user()->id)->first()->last_attendance_count;
		} else {
			$count = 0;
		}

		$stdatd = StudentAttendance::where("user_id", Auth::user()->id)->get();

		foreach($stdatd as $atd){
			if($atd->attendance->course_id == $activity->topic->course_id && $atd->is_attend == 1){
				$count++;
			}
		}

		$max_session_reached = false;
		if($count >= $cs->max_course_session) {
			$max_session_reached = true;
		}

		// Check if the user is really has the activity unlocked
		$progress = Progress::where("student_id", Auth::user()->id)->where("activity_id", $activity->id)->first();
		$actually_not_unlocked = false;

		if($progress->status == "locked"){
			$actually_not_unlocked = true;
		}

		if($max_session_reached || $actually_not_unlocked){
			return abort(403);
		}

		$original_link = $activity->link;
		$preview_link = $original_link;
		if (strpos($original_link, '/view?usp=sharing') !== false) {
			$preview_link = str_replace('/view?usp=sharing', '/preview', $original_link);
		} else if (strpos($original_link, 'youtu.be') !== false) {
			$cut_link = strstr($original_link, "?si=", true);
			$preview_link = str_replace('youtu.be', 'youtube.com/embed/', $cut_link);
			$preview_link .= "/embed";
		}

		$progress = Progress::where("student_id", Auth::user()->id)->where("activity_id", $activity->id)->first();
		if($progress->already_opened == "no"){
			Progress::findOrFail($progress->id)->update(["already_opened" => "yes"]);
		}

		return view("roles.student.course.preview", [
			"activity" => $activity,
			"preview_link" => $preview_link
		]);
	}

}
