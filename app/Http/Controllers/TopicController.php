<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Http\Request;

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
		return view("roles.teacher.topic-and-material.create-topic", [
			"course" => Course::where("id", $course_id)->where("visibility", "public")->first()
		]);
	}

	// Insert the new topic into database
	public function teacher_store(Request $request, $course_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		Topic::create(["course_id" => $course_id, "title" => $validatedData["title"]]);

		return redirect(route("teacher.mycourse.show", $course_id))->with("successAddTopic", "Successfully added new topic to the course!");
	}

	// Edit topic input page
	public function teacher_edit($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.edit-topic", [
			"topic" => Topic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	// Update the topic data in the database
	public function teacher_update(Request $request, $course_id, $topic_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		Topic::where("course_id", $course_id)->where("id", $topic_id)->update(["title" => $validatedData["title"]]);

		return redirect(route("teacher.topic.show", [$course_id, $topic_id]))->with("successEditTopic", "Successfully updated topic data!");
	}

	// Topic deletion confirmation
	public function teacher_delete($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.delete-topic-confirmation", [
			"topic" => Topic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	// Remove topic data from database
	public function teacher_destroy($course_id, $topic_id){
		Topic::destroy("id", $topic_id);

		return redirect(route("teacher.mycourse.show", $course_id))->with("successDeleteTopic", "Successfully deleted topic from the course!");
	}
}
