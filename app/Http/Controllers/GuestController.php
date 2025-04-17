<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\CurriculumTopic;
use App\Models\CurriculumActivity;
use App\Models\TrialClassResource;

class GuestController extends Controller {
	// ===== GUEST ===== //
	// Showing list of available courses in Sangnila LMS
	public function index() {
		$top_numbers = [];
		$top_courses = [];

		$courses = Course::withCount('students')
			->where('status', 'active')
			->orderBy('students_count', 'desc')
			->orderBy('course_name', 'asc')
			->get();

		foreach($courses as $c){
			if(!in_array($c->id, $top_numbers) && count($top_numbers) < 4){
				$top_numbers[] = $c->students->count();
				$top_courses[] = $c->id;
			}
		}

		return view('roles.guest.index', [
			'courses' => $courses,
			'top_courses' => $top_courses,
		]);
	}

	// Showing course details with accessible topics and activities
	public function show($course_id) {
		$course = Course::findOrFail($course_id);

		return view('roles.guest.show', [
			'course' => $course,
			'topics' => CurriculumTopic::where("course_id", $course->id)->get()
		]);
	}

	public function preview($course_id, $trial_class_resource_id){
		$trial_class_resource = TrialClassResource::findOrFail($trial_class_resource_id);

		$original_link = $trial_class_resource->material_link;

		// return $original_link;

		$unavailable = false;
		if(!$original_link || $original_link == null || ($original_link && $original_link == '')){
			$unavailable = true;
		}

		$haystack = $original_link;
		$needles = ["www.google", "N/A"];

		foreach ($needles as $needle) {
			if (strpos($haystack, $needle) !== false) {
				$unavailable = true;
				break;
			}
		}

		if($unavailable){
			return back()->with('danger', 'Oops! It looks like currently there is no material link available for this activity...');
		}

		$preview_link = $original_link;
		if (strpos($original_link, '/view?usp=sharing') !== false) {
			$preview_link = str_replace('/view?usp=sharing', '/preview', $original_link);
		} else if (strpos($original_link, 'youtu.be') !== false) {
			$cut_link = strstr($original_link, "?si=", true);
			$preview_link = str_replace('youtu.be', 'youtube.com/embed/', $cut_link);
			$preview_link .= "/embed";
		}

		return view('roles.guest.preview', [
			'trial_class_resource' => $trial_class_resource,
			"preview_link" => $preview_link
		]);
	}
}
