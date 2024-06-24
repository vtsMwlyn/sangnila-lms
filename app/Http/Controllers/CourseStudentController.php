<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\ImportedStudent;
use App\Models\Payment;
use App\Models\Progress;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;

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
		$unenrolled_courses = [];

		foreach($courses as $course){
			$alreadyEnrolled = false;
			foreach($student->enrolled_courses as $enrolled){
				if($enrolled->course_name == $course->course_name){
					$alreadyEnrolled = true;
					break;
				}
			}

			if(!$alreadyEnrolled){
				array_push($unenrolled_courses, $course);
			}
		}

		return view('roles.admin.student.index-assign', [
			'student' => $student,
			'courses' => $unenrolled_courses,
			"course_and_teachers" => $arr_ct
		]);
	}

	// Save the selected course into database
	public function store(Request $request, $student_id) {
		$validatedData = $request->validate([
			"course_name" => "required",
			"max_course_session" => "required",
			"teacher_name" => "required"
		]);

		$course = Course::where("course_name", $validatedData["course_name"])->first();
		$teacher = User::where("role_id", 2)->where("full_name", $validatedData["teacher_name"])->first();
		$student = User::where("role_id", 3)->where("id", $student_id)->first();

		CourseStudent::create([
			'student_id' => $student_id,
			'course_id' => $course->id,
			'max_course_session' => $validatedData["max_course_session"],
			"teacher_id" => $teacher->id,
			"is_imported" => 0
		]);

		$existingProgress = Progress::where('student_id', $student->id)
			->where('course_id', $course->id)
			->pluck('material_id')
			->toArray();

		foreach ($course->topics as $index1 => $topic) {
			foreach($topic->materials as $index2 => $material) {
				$newData = [
					'student_id' => $student->id,
					'material_id' => $material->id,
					'course_id' => $course->id,
				];

				if($index1 == 0 && $index2 == 0){
					$newData['status'] = 'unlocked';
				} else {
					$newData['status'] = 'locked';
				}

				if (!in_array($material->id, $existingProgress)) {
					Progress::create($newData);
				}
			}
		}

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
		$CourseStudent = CourseStudent::where('student_id', $student_id)->where('course_id', $course_id)->first();
		CourseStudent::destroy($CourseStudent->id);
		return redirect(route('admin.student.show', $student_id))->with("successUnassignFromCourse", "Successfully unassigned the student from the course!");;
	}

	// Batch assign student to course
	public function batch_assign($course_id){
		$course = Course::where("id", $course_id)->first();
		$students = User::where("role_id", 3)->get();

		$filtered = $students->filter(function($student) use($course){
			$notEnrolledYet = true;
			foreach($student->enrolled_courses as $ec){
				if($ec->id == $course->id){
					$notEnrolledYet = false;
					break;
				}
			}

			return $notEnrolledYet;
		});

		return view("roles.admin.student.batch-assign", [
			"course" => $course,
			"allStudents" => $filtered,
			"allTeachers" => $course->teachers
		]);
	}

	public function batch_assign_store(Request $request, $course_id){
		$students = $request->studentName;
		$teachers = $request->teacherName;
		$maxcoursesessions = $request->maxCourseSession;

		$course = Course::where("id", $course_id)->first();

		foreach($students as $index => $student){
			$targetStudent = User::where("id", $student)->first();
			$targetTeacher = User::where("role_id", 2)->where("full_name", $teachers[$index])->first();

			CourseStudent::create([
				"student_id" => $targetStudent->id,
				"teacher_id" => $targetTeacher->id,
				"course_id" => $course->id,
				"max_course_session" => $maxcoursesessions[$index],
				"is_imported" => 0
			]);

			$existingProgress = Progress::where('student_id', $targetStudent->id)
				->where('course_id', $course->id)
				->pluck('material_id')
				->toArray();

			foreach ($course->topics as $index1 => $topic) {
				foreach($topic->materials as $index2 => $material) {
					$newData = [
						'student_id' => $targetStudent->id,
						'material_id' => $material->id,
						'course_id' => $course->id,
					];

					if($index1 == 0 && $index2 == 0){
						$newData['status'] = 'unlocked';
					} else {
						$newData['status'] = 'locked';
					}

					if (!in_array($material->id, $existingProgress)) {
						Progress::create($newData);
					}
				}
			}
		}

		return redirect(route("admin.course.show", $course->id))->with("successBatchAssign", "Successfully batch-assigned students!");

	}

	// Import old existing student data
	public function import_student_data($course_id){
		$course = Course::where("id", $course_id)->first();

		return view("roles.admin.student.import-student", [
			"course" => $course,
			"education_levels" => ["Elementary School", "Junior High School", "Senior High School", "College", "Professional"]
		]);
	}

	public function import_student_data_store(Request $request, $course_id){
		$course = Course::where("id", $course_id)->first();

		foreach($request->inp_full_name as $index => $student_name){
			// Prevent duplicate email
			$existingUserWithSameEmail = User::where("email", $request->inp_email[$index])->get();
			$n = $existingUserWithSameEmail->count();

			if($n == 0){
				$generatedEmail = $request->inp_email[$index];
			} else {
				$dotPosition = strpos($request->inp_email[$index], '.');
				$localPart = substr($request->inp_email[$index], 0, $dotPosition);
				$domainPart = substr($request->inp_email[$index], $dotPosition);

				$generatedEmail = $localPart . ($n + 1) . $domainPart;
			}

			$newUser = User::create([
				"full_name" => $student_name,
				"email" => $generatedEmail,
				"role_id" => 3,
				"status" => "enabled",
				"email_verified_at" => now(), //soon will be removed
				"password" => Hash::make(trans("strings.default_password"))
			]);

			UserDetail::create([
				"user_id" => $newUser->id,
				"gender" => $request->inp_gender[$index],
				"phone_number" => $request->inp_phone_number[$index],
				"city_of_birth" => $request->inp_cob[$index],
				"date_of_birth" => $request->inp_dob[$index],
				"name_parent" => $request->inp_name_parent[$index],
				"phone_parent" => $request->inp_phone_parent[$index],
				"school_name" => $request->inp_school_name[$index],
				"student_level" => $request->inp_student_level[$index],
			]);

			CourseStudent::create([
				"course_id" => $course->id,
				"student_id" => $newUser->id,
				"teacher_id" => $request->inp_teacher_name[$index],
				"is_imported" => 1,
				"max_course_session" => $request->inp_max_course_session[$index]
			]);

			ImportedStudent::create([
				"student_id" => $newUser->id,
				"course_id" => $course->id,
				"last_attendance_count" => $request->inp_last_attendance_count[$index]
			]);

			$targetFound = false;
			foreach($course->topics as $topic){
				foreach($topic->materials as $material){
					if($material->id != $request->inp_last_material_unlocked[$index]){
						Progress::create([
							"student_id" => $newUser->id,
							"material_id" => $material->id,
							"course_id" => $course->id,
							"status" => "unlocked"
						]);
					} else {
						Progress::create([
							"student_id" => $newUser->id,
							"material_id" => $material->id,
							"course_id" => $course->id,
							"status" => "unlocked"
						]);

						$targetFound = true;
						break;
					}
				}

				if($targetFound){
					break;
				}
			}
		}

		return redirect(route("admin.course.show", $course_id))->with("successImportStudent", "Students data imported successfully!");
	}

}
