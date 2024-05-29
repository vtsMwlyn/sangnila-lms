<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseTopic;
use Illuminate\Http\Request;

class MaterialController extends Controller {
	// ===== TEACHER ===== //
	// New material input page
	public function teacher_create($topic_id) {
		$topic = CourseTopic::where("id", $topic_id)->first();
		return view('roles.teacher.topic-and-material.create-material', [
			'topic' => $topic
		]);
	}

	// Insert the new material data into database
	public function teacher_store(Request $request, $topic_id) {
		$topic = CourseTopic::where("id", $topic_id)->first();
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url"
		]);

		CourseMaterial::create(["course_topic_id" => $topic->id, "title" => $validatedData["title"], "link" => $validatedData["link"]]);

		return redirect(route('teacher.topic.show', [$topic->course->id, $topic->id]))->with("successUploadMaterial", "Successfully uploaded new material to the topic!");
	}

	// Edit material input page
	public function teacher_edit($material_id) {
		$material = CourseMaterial::findOrFail($material_id);
		return view('roles.teacher.topic-and-material.edit-material', [
			'material' => $material
		]);
	}

	// Update the material data in the database
	public function teacher_update(Request $request, $material_id) {
		$material = CourseMaterial::findOrFail($material_id);
		$data = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url"
		]);
		CourseMaterial::findOrFail($material_id)->update($data);

		return redirect(route('teacher.topic.show', [$material->course_topic->course->id, $material->course_topic->id]))->with("successEditMaterial", "Successfully update material data!");
	}

	// Material deletion confirmation
	public function teacher_delete($material_id) {
		return view("roles.teacher.topic-and-material.delete-material-confirmation", [
			"material" => CourseMaterial::where("id", $material_id)->first()
		]);
	}

	// Remove material data from database
	public function teacher_destroy($material_id) {
		$material = CourseMaterial::where("id", $material_id)->first();

		CourseMaterial::destroy("id", $material->id);

		return redirect(route("teacher.topic.show", [$material->course_topic->course->id, $material->course_topic->id]))->with("successDeleteMaterial", "Successfully deleted material from the topic!");
	}

}
