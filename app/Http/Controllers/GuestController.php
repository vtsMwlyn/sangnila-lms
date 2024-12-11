<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CurriculumTopic;
use Illuminate\Http\Request;

class GuestController extends Controller {
	// ===== GUEST ===== //
	// Showing list of available courses in Sangnila LMS
	public function index() {
		$courses = Course::where('status', 'active')->get();
		return view('roles.guest.index', [
			'courses' => $courses,
		]);
		//
	}

	// Showing course details with accessible topics and activities
	public function show($course_id) {
		$course = Course::findOrFail($course_id);

		return view('roles.guest.show', [
			'course' => $course,
			'topics' => CurriculumTopic::where("course_id", $course->id)->get()
		]);
	}
}
