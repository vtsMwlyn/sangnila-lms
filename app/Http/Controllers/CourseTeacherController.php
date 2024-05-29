<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseTeacher;

class CourseTeacherController extends Controller {
	// ===== ADMIN ===== //
	// Shows a page to select a course to assign to the teacher
	public function show($teacher_id) {
		$role = Role::where('role_name', 'Teacher')->first();
		$user = User::where('id', $teacher_id)->where('role_id', $role->id)->first();

		$existingCourseIds = CourseTeacher::where('user_id', $teacher_id)->get()->pluck('course_id')->toArray();
		$courses = Course::where('visibility', 'public')
			->whereNotIn('id', $existingCourseIds)
			->get();

		return view('roles.admin.teacher.index-assign', [
			'user' => $user,
			'courses' => $courses
		]);
	}

	// Save the course into database to teacher's assigned course
	public function assign(Request $request, $teacher_id){
		$selectedNewCourse = Course::where("course_name", $request["course_name"])->first();
		$alreadyExist = CourseTeacher::where("course_id", $selectedNewCourse["id"])->where("user_id", $teacher_id)->first();

		if($alreadyExist){
			return back()->with("duplicateCourseAssignment", "This teacher is already assigned to the course!");
		}

		CourseTeacher::create(["user_id" => $teacher_id, "course_id" => $selectedNewCourse["id"]]);

		return redirect(route("admin.teacher.show", $teacher_id))->with("successAssignToCourse", "Successfully assigned the teacher to the course!");
	}

	// Unassign teacher from a course confirmation
	public function delete($teacher_id, $course_id){
		$role = Role::where('role_name', 'Teacher')->first();
		$teacher = User::where('role_id', $role->id)->where('id', $teacher_id)->first();
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		return view('roles.admin.teacher.destroy', [
			'teacher' => $teacher,
			'course' => $course
		]);
	}

	// Remove the course from teacher's assigned course in the database
	public function unassign(Request $request, $teacher_id, $course_id) {
		// $course = Course::findOrFail($course_id);
		// $teachers = $course->teachers;
		// return view(admin.course.unassign);
		$targettedData = CourseTeacher::where("user_id", $teacher_id)->where("course_id", $course_id)->first();
		CourseTeacher::destroy($targettedData->id);

		return redirect(route("admin.teacher.show", $teacher_id))->with("successUnassignFromCourse", "Successfully unassigned the teacher from the course!");

	}

}
