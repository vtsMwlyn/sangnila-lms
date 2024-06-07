<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use App\Models\Topic;
use Illuminate\Http\Request;

class MaterialController extends Controller {
	// ===== TEACHER ===== //
	// New material input page
	public function teacher_create($topic_id) {
		$topic = Topic::where("id", $topic_id)->first();
		return view('roles.teacher.topic-and-material.create-material', [
			'topic' => $topic
		]);
	}

	// Insert the new material data into database
	public function teacher_store(Request $request, $topic_id) {
		$topic = Topic::where("id", $topic_id)->first();
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url"
		]);

		Material::create(["topic_id" => $topic->id, "title" => $validatedData["title"], "link" => $validatedData["link"]]);

		return redirect(route('teacher.topic.show', [$topic->course->id, $topic->id]))->with("successUploadMaterial", "Successfully uploaded new material to the topic!");
	}

	// Edit material input page
	public function teacher_edit($material_id) {
		$material = Material::findOrFail($material_id);
		return view('roles.teacher.topic-and-material.edit-material', [
			'material' => $material
		]);
	}

	// Update the material data in the database
	public function teacher_update(Request $request, $material_id) {
		$material = Material::findOrFail($material_id);
		$data = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url"
		]);
		Material::findOrFail($material_id)->update($data);

		return redirect(route('teacher.topic.show', [$material->topic->course->id, $material->topic->id]))->with("successEditMaterial", "Successfully update material data!");
	}

	// Material deletion confirmation
	public function teacher_delete($material_id) {
		return view("roles.teacher.topic-and-material.delete-material-confirmation", [
			"material" => Material::where("id", $material_id)->first()
		]);
	}

	// Remove material data from database
	public function teacher_destroy($material_id) {
		$material = Material::where("id", $material_id)->first();

		Material::destroy("id", $material->id);

		return redirect(route("teacher.topic.show", [$material->topic->course->id, $material->topic->id]))->with("successDeleteMaterial", "Successfully deleted material from the topic!");
	}

	// ===== STUDENT ===== //
	public function preview($material_id){
		$material = Material::where("id", $material_id)->first();

		$original_link = $material->link;
		$preview_link = $original_link;
		if (strpos($original_link, '/view?usp=sharing') !== false) {
			$preview_link = str_replace('/view?usp=sharing', '/preview', $original_link);
		} else if (strpos($original_link, 'youtu.be') !== false) {
			$cut_link = $newUrl = strstr($original_link, "?si=", true);
			$preview_link = str_replace('youtu.be', 'youtube.com/embed/', $cut_link);
			$preview_link .= "/embed";
		}

		return view("roles.student.course.preview", [
			"material" => $material,
			"preview_link" => $preview_link
		]);
	}

}
