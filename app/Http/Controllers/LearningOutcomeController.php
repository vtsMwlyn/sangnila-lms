<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\LearningOutcome;

class LearningOutcomeController extends Controller
{
    public function admin_store(Request $request, $course_id){
		$request->validate([
			"title" => "required|min:3",
			"number" => "required|numeric|min:1"
		]);

		$course = Course::findOrFail($course_id);

		LearningOutcome::create([
			"course_id" => $course->id,
			"title" => $request->title,
			"number" => $request->number
		]);

		return back()->with("successAddLearningOutcome", "Learning Outcome has been successfully added to this course!");
	}

	public function admin_update(Request $request, $course_id, $learning_outcome_id){
		$request->validate([
			"title" => "required|min:3",
			"number" => "required|numeric|min:1"
		]);

		$learning_outcome = LearningOutcome::findOrFail($learning_outcome_id);

		$learning_outcome->update([
			"title" => $request->title,
			"number" => $request->number
		]);

		return back()->with("successEditLearningOutcome", "Learning Outcome has been successfully edited to this course!");
	}

	public function admin_destroy($course_id, $learning_outcome_id){
		return "otw delete";
	}
}
