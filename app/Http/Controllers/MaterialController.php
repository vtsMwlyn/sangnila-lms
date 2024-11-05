<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\ImportedStudent;
use App\Models\Progress;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller {
	// ===== TEACHER ===== //
	// New material input page
	public function teacher_create($topic_id) {
		$topic = Topic::findOrFail($topic_id);
		return view('roles.teacher.topic-and-material.create-material', [
			'topic' => $topic
		]);
	}

	// Insert the new material data into database
	public function teacher_store(Request $request, $topic_id) {
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url",
			"desc" => "required|min:3"
		]);

		$topic = Topic::findOrFail($topic_id);

		try {
			Material::create([
				"topic_id" => $topic->id,
				"title" => $validatedData["title"],
				"link" => $validatedData["link"],
				"desc" => $validatedData["desc"]
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create material, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.topic.show', [$topic->course->id, $topic->id]))->with("successUploadMaterial", "Successfully uploaded new material to the topic!");
	}

	// Edit material input page
	public function teacher_edit($material_id) {
		return view('roles.teacher.topic-and-material.edit-material', [
			'material' => Material::findOrFail($material_id)
		]);
	}

	// Update the material data in the database
	public function teacher_update(Request $request, $material_id) {
		$data = $request->validate([
			"title" => "required|min:3",
			"link" => "required|url",
			"desc" => "required|min:3"
		]);

		$material = Material::findOrFail($material_id);

		try {
			$material->update($data);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit material, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('teacher.topic.show', [$material->topic->course->id, $material->topic->id]))->with("successEditMaterial", "Successfully update material data!");
	}

	// Material deletion confirmation
	public function teacher_delete($material_id) {
		return view("roles.teacher.topic-and-material.delete-material-confirmation", [
			"material" => Material::findOrFail($material_id)
		]);
	}

	// Remove material data from database
	public function teacher_destroy($material_id) {
		$material = Material::findOrFail($material_id);
		$cid = $material->topic->course->id;
		$tid = $material->topic->id;

		$material->delete();

		return redirect(route("teacher.topic.show", [$cid, $tid]))->with("successDeleteMaterial", "Successfully deleted material from the topic!");
	}

	// ===== STUDENT ===== //
	public function preview($material_id){
		$material = Material::findOrFail($material_id);

		// Check if the user reaches maximum course session
		$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $material->topic->course_id)->first();

		if($cs->is_imported){
			$count = ImportedStudent::where("course_id", $material->topic->course_id)->where("student_id", Auth::user()->id)->first()->last_attendance_count;
		} else {
			$count = 0;
		}

		$stdatd = StudentAttendance::where("user_id", Auth::user()->id)->get();

		foreach($stdatd as $atd){
			if($atd->attendance->course_id == $material->topic->course_id && $atd->is_attend == 1){
				$count++;
			}
		}

		$max_session_reached = false;
		if($count >= $cs->max_course_session) {
			$max_session_reached = true;
		}

		// Check if the user is really has the material unlocked
		$progress = Progress::where("student_id", Auth::user()->id)->where("material_id", $material->id)->first();
		$actually_not_unlocked = false;

		if($progress->status == "locked"){
			$actually_not_unlocked = true;
		}

		if($max_session_reached || $actually_not_unlocked){
			return abort(403);
		}

		$original_link = $material->link;
		$preview_link = $original_link;
		if (strpos($original_link, '/view?usp=sharing') !== false) {
			$preview_link = str_replace('/view?usp=sharing', '/preview', $original_link);
		} else if (strpos($original_link, 'youtu.be') !== false) {
			$cut_link = strstr($original_link, "?si=", true);
			$preview_link = str_replace('youtu.be', 'youtube.com/embed/', $cut_link);
			$preview_link .= "/embed";
		}

		$progress = Progress::where("student_id", Auth::user()->id)->where("material_id", $material->id)->first();
		if($progress->already_opened == "no"){
			Progress::findOrFail($progress->id)->update(["already_opened" => "yes"]);
		}

		return view("roles.student.course.preview", [
			"material" => $material,
			"preview_link" => $preview_link
		]);
	}

}
