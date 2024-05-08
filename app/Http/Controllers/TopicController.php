<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
	public function teacher_show($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.show-topic", [
			"topic" => CourseTopic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

    public function teacher_create($course_id){
		return view("roles.teacher.topic-and-material.create-topic", [
			"course" => Course::where("id", $course_id)->where("visibility", "public")->first()
		]);
	}

	public function teacher_store(Request $request, $course_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		CourseTopic::create(["course_id" => $course_id, "title" => $validatedData["title"]]);

		return redirect(route("teacher.mycourse.show", $course_id))->with("successAddTopic", "Successfully added new topic to the course!");
	}

	public function teacher_edit($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.edit-topic", [
			"topic" => CourseTopic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	public function teacher_update(Request $request, $course_id, $topic_id){
		$validatedData = $request->validate([
			"title" => "required|min:3"
		]);

		CourseTopic::where("course_id", $course_id)->where("id", $topic_id)->update(["title" => $validatedData["title"]]);

		return redirect(route("teacher.topic.show", [$course_id, $topic_id]))->with("successEditTopic", "Successfully updated topic data!");
	}

	public function teacher_delete($course_id, $topic_id){
		return view("roles.teacher.topic-and-material.delete-topic-confirmation", [
			"topic" => CourseTopic::where("course_id", $course_id)->where("id", $topic_id)->first()
		]);
	}

	public function teacher_destroy($course_id, $topic_id){
		CourseTopic::destroy("id", $topic_id);

		return redirect(route("teacher.mycourse.show", $course_id))->with("successDeleteTopic", "Successfully deleted topic from the course!");
	}
}
