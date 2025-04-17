<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use App\Models\Progress;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\LearningOutcome;
use App\Models\StudentAttendance;
use App\Rules\MinimumOneCheckbox;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningOutcomeActivity;

class ActivityController extends Controller {
	// ===== TEACHER ===== //
	// New activity input page
	public function teacher_create($topic_id) {
		$topic = Topic::findOrFail($topic_id);
		return view('roles.teacher.topic-and-activity.create-activity', [
			'topic' => $topic,
			"learning_outcomes" => LearningOutcome::where("course_id", $topic->course->id)->orderBy("number", "asc")->get()
		]);
	}

	// Insert the new activity data into database
	public function teacher_store(Request $request, $topic_id) {
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"link" => "nullable|url",
			"desc" => "required|min:3",
			"session" => "required|numeric|min:1"
		]);

		$topic = Topic::findOrFail($topic_id);

		try {
			$new_ac = Activity::create([
				"topic_id" => $topic->id,
				"title" => $validatedData["title"],
				"link" => $validatedData["link"],
				"desc" => e($validatedData["desc"]),
				"session" => $validatedData["session"]
			]);

			foreach(LearningOutcome::where("course_id", $topic->course->id)->orderBy("number", "asc")->get() as $i => $lo){
				if($request->learning_outcome[$i] == "on"){
					LearningOutcomeActivity::create([
						"activity_id" => $new_ac->id,
						"learning_outcome_id" => $lo->id
					]);
				}
			}
		}
		catch(Exception $e){
			return back()->with("danger", "System failed to create activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.course.topic.show', [$topic->course->id, $topic->id]))->with("success", "Successfully uploaded new activity to the topic!");
	}

	// Edit activity input page
	public function teacher_edit($activity_id) {
		$activity = Activity::findOrFail($activity_id);
		$learning_outcomes = LearningOutcome::where("course_id", $activity->topic->course->id)->orderBy("number", "asc")->get();

		$checkbox_values = [];

		if($learning_outcomes->count() > 0){
			foreach($learning_outcomes as $lo){
				$found = false;
				foreach($activity->learning_outcomes as $calo){
					if($calo->id == $lo->id){
						$found = true;
						break;
					}
				}

				if($found){
					array_push($checkbox_values, "on");
				}
				else {
					array_push($checkbox_values, "off");
				}
			}
		}

		return view('roles.teacher.topic-and-activity.edit-activity', [
			'activity' => $activity,
			"learning_outcomes" => $learning_outcomes,
			"checkbox_values" => $checkbox_values
		]);
	}

	// Update the activity data in the database
	public function teacher_update(Request $request, $activity_id) {
		$data = $request->validate([
			"title" => "required|min:3",
			"link" => "nullable|url",
			"desc" => "required|min:3",
			"session" => "required|numeric|min:1"
		]);

		$activity = Activity::findOrFail($activity_id);
		$course = $activity->topic->course;

		try {
			$activity->update([
				"title" => $request->title,
				"desc" => e($request->desc),
				"link" => $request->link,
				"session" => $request->session
			]);

			$learning_outcomes = LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get();
			if($learning_outcomes->count() > 0){
				foreach($request->learning_outcome as $i => $rlo){
					if($rlo == "on"){
						$existing_calo = LearningOutcomeActivity::where("learning_outcome_id", $learning_outcomes[$i]->id)->where("activity_id", $activity->id)->first();
						if(!$existing_calo){
							LearningOutcomeActivity::create([
								"activity_id" => $activity->id,
								"learning_outcome_id" => $learning_outcomes[$i]->id
							]);
						}
					}
					else {
						$existing_calo = LearningOutcomeActivity::where("learning_outcome_id", $learning_outcomes[$i]->id)->where("activity_id", $activity->id)->first();
						if($existing_calo){
							LearningOutcomeActivity::destroy($existing_calo->id);
						}
					}
				}
			}
		}
		catch(Exception $e){
			return back()->with("danger", "System failed to edit activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('teacher.course.topic.show', [$activity->topic->course->id, $activity->topic->id]))->with("success", "Successfully update activity data!");
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

		return redirect(route("teacher.course.topic.show", [$cid, $tid]))->with("warning", "Successfully deleted activity from the topic!");
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

		$stdatd = StudentAttendance::where("student_id", Auth::user()->id)->get();

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
