<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use App\Models\Progress;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\CurriculumTopic;
use App\Models\LearningOutcome;
use App\Rules\MinimumOneCheckbox;
use App\Models\CurriculumActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LearningOutcomeActivity;
use App\Models\LearningOutcomeCurriculumActivity;
use App\Models\StudentAttendance;

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
			return back()->with("danger", "System failed to create curriculum topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $new_curriculum_topic->id]))->with("success", "Successfully added new curriculum topic data!");
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
			return back()->with("danger", "System failed to edit curriculum topic, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, CurriculumTopic::find($curriculum_topic_id)->id]))->with("success", "Successfully updated the curriculum topic data!");
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

		return redirect(route('admin.course.show', $course_id))->with("warning", "Successfully removed the curriculum topic from " . $course->course_name . "!");
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
				"desc" => e($request->desc),
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
			return back()->with("danger", "System failed to create curriculum activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $ctopic->id]))->with("success", "Successfully added the curriculum activity data!");
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
			DB::beginTransaction();

			$cactivity = CurriculumActivity::findOrFail($curriculum_activity_id);
			$course = Course::findOrFail($course_id);

			$cactivity->update([
				"title" => $request->title,
				"desc" => e($request->desc),
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

			DB::commit();
		}
		catch(Exception $e){
			DB::rollBack();

			return back()->with("danger", "System failed to edit curriculum activity, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $cactivity->curriculum_topic->id]))->with("success", "Successfully updated the curriculum topic data!");
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

		return redirect(route('admin.course.curriculum.topic.details', [$course_id, $topic->id]))->with("warning", "Successfully removed the curriculum activity from " . $topic->title . "!");
	}


	public function copy_syllabus(Request $request, $course_id){
		try {
			DB::beginTransaction();

			$current_course = Course::findOrFail($course_id);
			$source_course = Course::findOrFail($request->course_id);

			CurriculumTopic::where('course_id', $current_course->id)->delete();
			LearningOutcome::where('course_id', $current_course->id)->delete();

			foreach($source_course->learning_outcomes as $lo){
				LearningOutcome::create([
					'course_id' => $current_course->id,
					'title' => $lo->title,
					'number' => $lo->number,
				]);
			}

			foreach($source_course->curriculum_topics as $ctopic){
				$ntopic = CurriculumTopic::create([
					"title" => $ctopic->title,
					"course_id" => $current_course->id
				]);

				foreach($ctopic->curriculum_activities as $cactivity){
					$nyuu = CurriculumActivity::create([
						"curriculum_topic_id" => $ntopic->id,
						"title" => $cactivity->title,
						"desc" => e($cactivity->desc),
						"link" => $cactivity->link,
						"session" => $cactivity->session
					]);

					foreach(LearningOutcomeCurriculumActivity::where('curriculum_activity_id', $cactivity->id)->get() as $loca){
						$elo = LearningOutcome::where('course_id', $current_course->id)->where('title', $loca->learning_outcome->title)->first();

						if ($elo) {
							LearningOutcomeCurriculumActivity::create([
								'learning_outcome_id' => $elo->id,
								'curriculum_activity_id' => $nyuu->id,
							]);
						}
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to copy syllabus data from the targetted course, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.course.show", $current_course->id))->with("success", "Successfully copied syllabus data from the targetted course!");
	}


	/* ===== TEACHER ====== */
	public function teacher_synchronize($course_id){
		try {
			DB::beginTransaction();

			$course = Course::findOrFail($course_id);

			Topic::where("course_id", $course_id)->where("user_id", Auth::user()->id)->delete();

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
						"desc" => e($activity->desc),
						"link" => $activity->link,
						"session" => $activity->session
					]);

					foreach($activity->learning_outcomes as $ctlo){
						LearningOutcomeActivity::create([
							"learning_outcome_id" => $ctlo->id,
							"activity_id" => $nyuu->id
						]);
					}
				}
			}
			
			// Auto unlock and update student progress
			$activities = Activity::whereHas('topic', function($query) use ($course){
				return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
			})->orderBy('session', 'asc')->get();

			foreach(CourseStudent::where('teacher_id', Auth::user()->id)->where('course_id', $course_id)->get() as $cs){
				$student = $cs->student;

				$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
					return $query->where('course_id', $course->id);
				})->get();

				$session_counter = 1;

				foreach($activities as $index => $activity){
					if($activity->session != $session_counter){
						$session_counter++;
					}

					Progress::create([
						"student_id" => $student->id,
						"activity_id" => $activity->id,
						"course_id" => $course->id,
						"status" => $session_counter <= $student_attendances->count() || $index == 0? 'unlocked' : 'locked',
					]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to synchronize your course topics and activities with the curriculum, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.mycourse.show", ['course_id' => $course_id, 'content' => 'topics and activities']))->with("success", "Your class' topic and activities have been successfully synchronized with the curriculum!");
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

			Topic::where("course_id", $course_id)->where("user_id", Auth::user()->id)->delete();

			$curriculum_topics = CurriculumTopic::where("course_id", $course_id)->get();

			$i = 0;
			$already_created_topics = [];

			foreach($curriculum_topics as $ctopic){
				if($request->selected[$i] == 'on'){
					$topique_id = 0;
					
					if(!in_array($ctopic->id, $already_created_topics)){
						$ntopic = Topic::create([
							"title" => $ctopic->title,
							"user_id" => Auth::user()->id,
							"course_id" => $course->id
						]);

						array_push($already_created_topics, $ctopic->id);
						
						$topique_id = $ntopic->id;
					}
					else {
						$etopic = Topic::where('course_id', $course->id)->where('title', $ctopic->title)->where('user_id', Auth::user()->id)->first();
						
						$topique_id = $etopic->id;
					}
					
					foreach($ctopic->curriculum_activities as $cactivity){
						if($request->selected[$i] == "on"){
							$nyuu = Activity::create([
								"topic_id" => $topique_id,
								"title" => $cactivity->title,
								"desc" => e($cactivity->desc),
								"link" => $cactivity->link,
								"session" => $cactivity->session,
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
			}

			// Auto unlock and update student progress
			$activities = Activity::whereHas('topic', function($query) use ($course){
				return $query->where('course_id', $course->id)->where('user_id', Auth::user()->id);
			})->orderBy('session', 'asc')->get();

			foreach(CourseStudent::where('teacher_id', Auth::user()->id)->where('course_id', $course_id)->get() as $cs){
				$student = $cs->student;

				$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
					return $query->where('course_id', $course->id);
				})->get();

				$session_counter = 1;

				foreach($activities as $index => $activity){
					if($activity->session != $session_counter){
						$session_counter++;
					}

					Progress::create([
						"student_id" => $student->id,
						"activity_id" => $activity->id,
						"course_id" => $course->id,
						"status" => $session_counter <= $student_attendances->count() || $index == 0? 'unlocked' : 'locked',
					]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();
			throw $e;
			// return back()->with("danger", "System failed to save selected syllabus topic and activities to your course's topics and activities. Please report this error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('teacher.mycourse.show', ['course_id' => $course_id, 'content' => 'topics and activities']))->with("success", "Successfully picked topics and activities from the curriculum");
	}
}
