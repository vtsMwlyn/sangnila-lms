<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentController extends Controller {
	// ===== ADMIN ===== //
	// Shows a page to select a course to assign to a student
	public function create($student_id) {
		$role = Role::where('role_name', 'Student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();

		$arr_ct = [];
		foreach(Course::all() as $c){
			$teacher_list = [];
			foreach($c->teachers as $teacher){
				array_push($teacher_list, $teacher->full_name);
			}

			array_push($arr_ct, ["course_name" => $c->course_name, "teachers" => $teacher_list]);
		}

		$courses = Course::where('visibility', 'public')->get();

		return view('roles.admin.student.index-assign', [
			'student' => $student,
			'courses' => $courses,
			"course_and_teachers" => $arr_ct
		]);
	}

	// Save the selected course into database
	public function store(Request $request, $student_id) {
		$validatedData = $request->validate([
			"course_name" => "required",
			"max_course_session" => "required"
		]);

		$targettedCourse = Course::where("course_name", $validatedData["course_name"])->first();

		CourseStudent::create([
			'user_id' => $student_id,
			'course_id' => $targettedCourse->id,
			'max_course_session' => $validatedData["max_course_session"]
		]);

		return redirect(route('admin.student.show', $student_id))->with("successAssignToCourse", "Successfully assigned the student to the course!");
	}

	// Unassign student from a course confirmation
	public function delete($student_id, $course_id) {
		$role = Role::where('role_name', 'student')->first();
		$student = User::where('role_id', $role->id)->where('id', $student_id)->first();
		$course = Course::where('visibility', 'public')->where('id', $course_id)->first();
		return view('roles.admin.student.destroy', [
			'student' => $student,
			'course' => $course
		]);
	}

	// Remove the course from student's assigned course in the database
	public function destroy($student_id, $course_id) {
		$CourseStudent = CourseStudent::where('user_id', $student_id)->where('course_id', $course_id)->first();
		CourseStudent::destroy($CourseStudent->id);
		return redirect(route('admin.student.show', $student_id))->with("successUnassignFromCourse", "Successfully unassigned the student from the course!");;
	}

}
