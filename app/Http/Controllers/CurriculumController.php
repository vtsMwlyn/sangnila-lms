<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\CurriculumTopic;
use App\Models\CurriculumMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CurriculumController extends Controller
{
	/* ===== ADMIN ===== */
	public function admin_create_topic($course_id){
		return view("roles.admin.curriculum.create-topic", [
			"course" => Course::findOrFail($course_id)
		]);
	}

	public function admin_store_topic(Request $request, $course_id){
		$request->validate(["topic_title" => "required|min:3"]);

		try {
			CurriculumTopic::create([
				"course_id" => $course_id,
				"title" => $request->topic_title
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create curriculum topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.show', $course_id))->with("successAddCurriculumTopic", "Successfully added new curriculum topic to the course!");
	}

	public function admin_edit_topic($course_id, $curriculum_topic_id){
		return view("roles.admin.curriculum.edit-topic", [
			"course" => Course::findOrFail($course_id),
			"curriculum_topic" => CurriculumTopic::findOrFail($curriculum_topic_id)
		]);
	}

	public function admin_update_topic(Request $request, $course_id, $curriculum_topic_id){
		$request->validate(["topic_title" => "required|min:3"]);

		try {
			CurriculumTopic::findOrFail($curriculum_topic_id)->update(["title" => $request->topic_title]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit curriculum topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, CurriculumTopic::find($curriculum_topic_id)->id]))->with("successEditCurriculumTopic", "Successfully updated the curriculum topic data!");
	}

	public function admin_delete_topic($course_id, $curriculum_topic_id){
		return view("roles.admin.curriculum.delete-topic", [
			"course" => Course::findOrFail($course_id),
			"curriculum_topic" => CurriculumTopic::findOrFail($curriculum_topic_id)
		]);
	}

	public function admin_destroy_topic($course_id, $curriculum_topic_id){
		$course = Course::findOrFail($course_id);
		CurriculumTopic::destroy($curriculum_topic_id);

		return redirect(route('admin.course.show', $course_id))->with("successDeleteCurriculumTopic", "Successfully removed the curriculum topic from " . $course->course_name . "!");
	}

	public function admin_topic_details($course_id, $curriculum_topic_id){
		return view("roles.admin.curriculum.topic-details", [
			"course" => Course::findOrFail($course_id),
			"curriculum_topic" => CurriculumTopic::findOrFail($curriculum_topic_id)
		]);
	}


	public function admin_create_material($course_id, $curriculum_topic_id){
		return view("roles.admin.curriculum.create-material", [
			"course" => Course::findOrFail($course_id),
			"curriculum_topic" => CurriculumTopic::findOrFail($curriculum_topic_id)
		]);
	}

	public function admin_store_material(Request $request, $course_id, $curriculum_topic_id){
		$request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "required|url"
		]);

		$ctopic = CurriculumTopic::findOrFail($curriculum_topic_id);

		try {
			CurriculumMaterial::create([
				"title" => $request->title,
				"desc" => $request->desc,
				"link" => $request->link,
				"curriculum_topic_id" => $ctopic->id
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create curriculum material, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $ctopic->id]))->with("successAddCurriculumMaterial", "Successfully added the curriculum material data!");
	}

	public function admin_edit_material($course_id, $curriculum_topic_id, $curriculum_material_id){
		return view("roles.admin.curriculum.edit-material", [
			"course" => Course::findOrFail($course_id),
			"curriculum_material" => CurriculumMaterial::findOrFail($curriculum_material_id)
		]);
	}

	public function admin_update_material(Request $request, $course_id, $curriculum_topic_id, $curriculum_material_id){
		$request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "required|url"
		]);

		$cmaterial = CurriculumMaterial::findOrFail($curriculum_material_id);

		try {
			$cmaterial->update([
				"title" => $request->title,
				"desc" => $request->desc,
				"link" => $request->link
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit curriculum material, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $cmaterial->curriculum_topic->id]))->with("successEditCurriculumMaterial", "Successfully updated the curriculum topic data!");
	}

	public function admin_delete_material($course_id, $curriculum_topic_id, $curriculum_material_id){
		return view("roles.admin.curriculum.delete-material", [
			"course" => Course::findOrFail($course_id),
			"curriculum_material" => CurriculumMaterial::findOrFail($curriculum_material_id)
		]);
	}

	public function admin_destroy_material($course_id, $curriculum_topic_id, $curriculum_material_id){
		$topic = CurriculumMaterial::findOrFail($curriculum_topic_id);

		CurriculumMaterial::destroy($curriculum_material_id);

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $topic->id]))->with("successDeleteCurriculumMaterial", "Successfully removed the curriculum material from " . $topic->title . "!");
	}


	/* ===== TEACHER ====== */
	public function teacher_synchronize($course_id){
		try {
			DB::beginTransaction();

			$current_topics = Topic::where("course_id", $course_id)->where("user_id", Auth::user()->id)->get();

			foreach($current_topics as $utopic){
				Topic::destroy($utopic->id);
			}

			$curriculum_topics = CurriculumTopic::where("course_id", $course_id)->get();

			foreach($curriculum_topics as $ctopic){
				$ntopic = Topic::create([
					"title" => $ctopic->title,
					"user_id" => Auth::user()->id,
					"course_id" => $course_id
				]);

				foreach($ctopic->curriculum_materials as $material){
					Material::create([
						"topic_id" => $ntopic->id,
						"title" => $material->title,
						"desc" => $material->desc,
						"link" => $material->link
					]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to synchronize your course topics and materials with the curriculum, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route("teacher.mycourse.show", $course_id))->with("successSynchronizeCurriculum", "Your class' topic and materials have been successfully synchronized with the curriculum!");
	}
}
