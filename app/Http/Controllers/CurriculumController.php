<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\CurriculumTopic;
use App\Models\CurriculumActivity;
use App\Models\LearningOutcome;
use App\Models\LearningOutcomeActivity;
use App\Models\LearningOutcomeCurriculumActivity;
use App\Rules\MinimumOneCheckbox;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
			$new_curriculum_topic = CurriculumTopic::create([
				"course_id" => $course_id,
				"title" => $request->topic_title
			]);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create curriculum topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $new_curriculum_topic->id]))->with("successEditCurriculumTopic", "Successfully added new curriculum topic data!");
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


	public function admin_create_activity($course_id, $curriculum_topic_id){
		$course = Course::findOrFail($course_id);

		return view("roles.admin.curriculum.create-activity", [
			"course" => $course,
			"curriculum_topic" => CurriculumTopic::findOrFail($curriculum_topic_id),
			"learning_outcomes" => LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get()
		]);
	}

	public function admin_store_activity(Request $request, $course_id, $curriculum_topic_id){
		$request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "nullable|url",
			"session" => "required|numeric|min:0|not_in:0"
		]);


		$ctopic = CurriculumTopic::findOrFail($curriculum_topic_id);
		$course = Course::findOrFail($course_id);

		try {
			$new_ca = CurriculumActivity::create([
				"title" => $request->title,
				"desc" => $request->desc,
				"link" => $request->link,
				"curriculum_topic_id" => $ctopic->id,
				"session" => $request->session
			]);

			$learning_outcomes = LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get();
            if($learning_outcomes->count()){
				foreach($learning_outcomes as $i => $lo){
					if($request->learning_outcome[$i] == "on"){
						LearningOutcomeCurriculumActivity::create([
							"curriculum_activity_id" => $new_ca->id,
							"learning_outcome_id" => $lo->id
						]);
					}
				}
			}

		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create curriculum activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $ctopic->id]))->with("successAddCurriculumActivity", "Successfully added the curriculum activity data!");
	}

	public function admin_edit_activity($course_id, $curriculum_topic_id, $curriculum_activity_id){
		$course = Course::findOrFail($course_id);
		$learning_outcomes = LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get();

		$cactivity = CurriculumActivity::findOrFail($curriculum_activity_id);
		$checkbox_values = [];

		if($learning_outcomes->count()){
			foreach($learning_outcomes as $lo){
				$found = false;
				foreach($cactivity->learning_outcomes as $calo){
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

		return view("roles.admin.curriculum.edit-activity", [
			"course" => $course,
			"curriculum_activity" => $cactivity,
			"learning_outcomes" => $learning_outcomes,
			"checkbox_values" => $checkbox_values
		]);
	}

	public function admin_update_activity(Request $request, $course_id, $curriculum_topic_id, $curriculum_activity_id){
		$request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "nullable|url",
			"session" => "required|numeric|min:0|not_in:0"
		]);

		try {
			$cactivity = CurriculumActivity::findOrFail($curriculum_activity_id);
			$course = Course::findOrFail($course_id);

			$cactivity->update([
				"title" => $request->title,
				"desc" => $request->desc,
				"link" => $request->link,
				"session" => $request->session
			]);

			$learning_outcomes = LearningOutcome::where("course_id", $course->id)->orderBy("number", "asc")->get();

			if($learning_outcomes->count()){
				foreach($request->learning_outcome as $i => $rlo){
					if($rlo == "on"){
						$existing_calo = LearningOutcomeCurriculumActivity::where("learning_outcome_id", $learning_outcomes[$i]->id)->where("curriculum_activity_id", $cactivity->id)->first();
						if(!$existing_calo){
							LearningOutcomeCurriculumActivity::create([
								"curriculum_activity_id" => $cactivity->id,
								"learning_outcome_id" => $learning_outcomes[$i]->id
							]);
						}
					}
					else {
						$existing_calo = LearningOutcomeCurriculumActivity::where("learning_outcome_id", $learning_outcomes[$i]->id)->where("curriculum_activity_id", $cactivity->id)->first();
						if($existing_calo){
							LearningOutcomeCurriculumActivity::destroy($existing_calo->id);
						}
					}
				}
			}
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to edit curriculum activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $cactivity->curriculum_topic->id]))->with("successEditCurriculumActivity", "Successfully updated the curriculum topic data!");
	}

	public function admin_delete_activity($course_id, $curriculum_topic_id, $curriculum_activity_id){
		return view("roles.admin.curriculum.delete-activity", [
			"course" => Course::findOrFail($course_id),
			"curriculum_activity" => CurriculumActivity::findOrFail($curriculum_activity_id)
		]);
	}

	public function admin_destroy_activity($course_id, $curriculum_topic_id, $curriculum_activity_id){
		$topic = CurriculumTopic::findOrFail($curriculum_topic_id);
		$activity = CurriculumActivity::findOrFail($curriculum_activity_id);

		$activity->delete();

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $topic->id]))->with("successDeleteCurriculumActivity", "Successfully removed the curriculum activity from " . $topic->title . "!");
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

				foreach($ctopic->curriculum_activities as $activity){
					$nyuu = Activity::create([
						"topic_id" => $ntopic->id,
						"title" => $activity->title,
						"desc" => $activity->desc,
						"link" => $activity->link
					]);

					foreach($activity->learning_outcomes as $ctlo){
						LearningOutcomeActivity::create([
							"learning_outcome_id" => $ctlo->id,
							"activity_id" => $nyuu->id
						]);
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to synchronize your course topics and activities with the curriculum, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route("teacher.mycourse.show", $course_id))->with("successSynchronizeCurriculum", "Your class' topic and activities have been successfully synchronized with the curriculum!");
	}

	public function teacher_pick_course($course_id){
		$course = Course::findOrFail($course_id);
		$curriculum_topics = CurriculumTopic::where("course_id", $course_id)->get();

		return view("roles.teacher.topic-and-activity.pick-from-curriculum", [
			"curriculum_topics" => $curriculum_topics,
			"course" => $course
		]);
	}

	public function teacher_save_picked_course(Request $request, $course_id){
		$request->validate(["selected" => new MinimumOneCheckbox]);

		try {
			DB::beginTransaction();

			$course = Course::findOrFail($course_id);

			$current_topics = Topic::where("course_id", $course_id)->where("user_id", Auth::user()->id)->get();

			foreach($current_topics as $utopic){
				Topic::destroy($utopic->id);
			}

			$curriculum_topics = CurriculumTopic::where("course_id", $course_id)->get();

			$i = 0;
			foreach($curriculum_topics as $ctopic){
				$ntopic = Topic::create([
					"title" => $ctopic->title,
					"user_id" => Auth::user()->id,
					"course_id" => $course->id
				]);

				foreach($ctopic->curriculum_activities as $cactivity){
					if($request->selected[$i] == "on"){
						$nyuu = Activity::create([
							"topic_id" => $ntopic->id,
							"title" => $cactivity->title,
							"desc" => $cactivity->desc,
							"link" => $cactivity->link
						]);

						foreach($cactivity->learning_outcomes as $ctlo){
							LearningOutcomeActivity::create([
								"learning_outcome_id" => $ctlo->id,
								"activity_id" => $nyuu->id
							]);
						}
					}

					$i++;
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to save selected syllabus topic and activities to your course's topics and activities. Please report this error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.mycourse.show', $course->id))->with("successPickFromCurriculum", "Successfully picked topics and activities from the curriculum");
	}
}
