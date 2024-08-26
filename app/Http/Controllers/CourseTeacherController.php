<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseTeacher;
use Illuminate\Support\Facades\DB;

class CourseTeacherController extends Controller {
	// ===== ADMIN ===== //
	// Shows a page to select a course to assign to the teacher
	public function show($teacher_id) {
		$user = User::findOrFail($teacher_id);

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
		try {
			DB::beginTransaction();

			foreach($request->courses_list as $course_to_assign){
				$selectedNewCourse = Course::findOrFail($course_to_assign);

				CourseTeacher::create(["user_id" => $teacher_id, "course_id" => $selectedNewCourse->id]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to assign the courses to the teacher, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.teacher.show", $teacher_id))->with("successAssignToCourse", "Successfully assigned the teacher to the course!");
	}

	// Unassign teacher from a course confirmation
	public function delete($teacher_id, $course_id){
		return view('roles.admin.teacher.destroy', [
			'teacher' => User::findOrFail($teacher_id),
			'course' => Course::findOrFail($course_id)
		]);
	}

	// Remove the course from teacher's assigned course in the database
	public function unassign($teacher_id, $course_id) {
		try {
			$targettedData = CourseTeacher::where("user_id", $teacher_id)->where("course_id", $course_id)->first();

			CourseTeacher::destroy($targettedData->id);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to assign the course from the teacher, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.teacher.show", $teacher_id))->with("successUnassignFromCourse", "Successfully unassigned the teacher from the course!");

	}

}
