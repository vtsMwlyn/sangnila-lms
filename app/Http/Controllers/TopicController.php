<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
	// ===== TEACHER ===== //
	// Shows a topic's details and all materials in the topic
	public function teacher_show($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.show-topic", [
			"topic" => Topic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	// Create new topic input page
    public function teacher_create($course_id){
		$course = Course::findOrFail($course_id);
		$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

		return view("roles.teacher.topic-and-material.create-topic", [
			"course" => $course,
			"topics" => $topics,
		]);
	}

	// Insert the new topic into database
	public function teacher_store(Request $request, $course_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		try {
			Topic::create(["course_id" => $course_id, "title" => $validatedData["title"], "user_id" => Auth::user()->id]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route("teacher.mycourse.show", $course_id))->with("successAddTopic", "Successfully added new topic to the course!");
	}

	// Edit topic input page
	public function teacher_edit($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.edit-topic", [
			"topic" => Topic::findOrFail($topic_id),
			"course" => Course::findOrFail($course_id),
		]);
	}

	// Update the topic data in the database
	public function teacher_update(Request $request, $course_id, $topic_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		try {
			Topic::findOrFail($topic_id)->update(["title" => $validatedData["title"]]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.mycourse.topic.show", [$course_id, $topic_id]))->with("successEditTopic", "Successfully updated topic data!");
	}

	// Topic deletion confirmation
	public function teacher_delete($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.delete-topic-confirmation", [
			"topic" => Topic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	// Remove topic data from database
	public function teacher_destroy($course_id, $topic_id){
		Topic::findOrFail($topic_id)->delete();

		return redirect(route("teacher.mycourse.show", $course_id))->with("successDeleteTopic", "Successfully deleted topic from the course!");
	}
}
