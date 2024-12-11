<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Progress;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\ImportedStudent;
use App\Rules\MinimumOneCheckbox;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CourseStudentController extends Controller {
	// ===== ADMIN ===== //
	// Shows a page to select a course to assign to a student
	public function create($student_id) {
		$student = User::findOrFail($student_id);

		$arr_ct = [];
		foreach(Course::all() as $c){
			$teacher_list = [];
			foreach($c->teachers as $teacher){
				array_push($teacher_list, $teacher);
			}

			array_push($arr_ct, ["course_id" => $c->id, "teachers" => $teacher_list]);
		}

		$courses = Course::where('status', 'active')->get();
		$unenrolled_courses = [];

		foreach($courses as $course){
			$alreadyEnrolled = false;
			foreach($student->enrolled_courses as $enrolled){
				if($enrolled->id == $course->id){
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
			"course" => "required",
			"max_course_session" => "required",
			"teacher" => "required"
		]);

		try {
			DB::beginTransaction();

			$course = Course::find(json_decode($validatedData["course"])->id);
			$teacher = User::find($validatedData["teacher"]);
			$student = User::find($student_id);

			CourseStudent::create([
				'student_id' => $student_id,
				'course_id' => $course->id,
				'max_course_session' => $validatedData["max_course_session"],
				"teacher_id" => $teacher->id,
				"is_imported" => 0
			]);

			$existingProgress = Progress::where('student_id', $student->id)
				->where('course_id', $course->id)
				->pluck('activity_id')
				->toArray();

			$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

			foreach ($topics as $index1 => $topic) {
				foreach($topic->activities as $index2 => $activity) {
					$newData = [
						'student_id' => $student->id,
						'activity_id' => $activity->id,
						'course_id' => $course->id,
					];

					if($index1 == 0 && $index2 == 0){
						$newData['status'] = 'unlocked';
					} else {
						$newData['status'] = 'locked';
					}

					if (!in_array($activity->id, $existingProgress)) {
						Progress::create($newData);
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to assign courses to the student, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route('admin.student.show', $student_id))->with("successAssignToCourse", "Successfully assigned the student to the course!");
	}

	// Unassign student from a course confirmation
	public function delete($student_id, $course_id) {
		return view('roles.admin.student.destroy', [
			'student' => User::findOrFail($student_id),
			'course' => Course::findOrFail($course_id)
		]);
	}

	// Remove the course from student's assigned course in the database
	public function destroy($student_id, $course_id) {
		try {
			$CourseStudent = CourseStudent::where('student_id', $student_id)->where('course_id', $course_id)->first();
			CourseStudent::destroy($CourseStudent->id);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to unassign the course from the student, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.student.show', $student_id))->with("successUnassignFromCourse", "Successfully unassigned the student from the course!");;
	}

	// Batch assign student to course
	public function batch_assign($course_id){
		$course = Course::findOrFail($course_id);
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

		$course = Course::findOrFail($course_id);

		try {
			DB::beginTransaction();

			foreach($students as $index => $student){
				$targetStudent = User::find($student);
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
					->pluck('activity_id')
					->toArray();

				$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

				foreach ($topics as $index1 => $topic) {
					foreach($topic->activities as $index2 => $activity) {
						$newData = [
							'student_id' => $targetStudent->id,
							'activity_id' => $activity->id,
							'course_id' => $course->id,
						];

						if($index1 == 0 && $index2 == 0){
							$newData['status'] = 'unlocked';
						} else {
							$newData['status'] = 'locked';
						}

						if (!in_array($activity->id, $existingProgress)) {
							Progress::create($newData);
						}
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to batch assign these students to the course, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.course.show", $course->id))->with("successBatchAssign", "Successfully batch-assigned students!");

	}

	// Import old existing student data
	public function import_student_data($course_id){
		$course = Course::findOrFail($course_id);

		$activities = [];
		foreach($course->topics as $topic){
			array_push($activities, $topic->activities);
		}

		return view("roles.admin.student.import-student", [
			"course" => $course,
			"activities" => $activities,
			"education_levels" => ["Elementary School", "Junior High School", "Senior High School", "College", "Professional"]
		]);
	}

	public function import_student_data_store(Request $request, $course_id){
		$course = Course::findOrFail($course_id);

		try {
			DB::beginTransaction();

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

				if($request->is_new_student[$index] == "Yes"){
					$student = User::create([
						"full_name" => $student_name,
						"email" => $generatedEmail,
						"role_id" => 3,
						"status" => "enabled",
						"email_verified_at" => now(), //soon will be removed
						"password" => Hash::make(trans("strings.default_password"))
					]);

					UserDetail::create([
						"user_id" => $student->id,
						"gender" => $request->inp_gender[$index],
						"phone_number" => $request->inp_phone_number[$index],
						"city_of_birth" => $request->inp_cob[$index],
						"date_of_birth" => $request->inp_dob[$index],
						"name_parent" => $request->inp_name_parent[$index],
						"phone_parent" => $request->inp_phone_parent[$index],
						"school_name" => $request->inp_school_name[$index],
						"student_level" => $request->inp_student_level[$index],
					]);
				}
				else {
					$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
					if(!$student){
						continue;
					}
				}

				CourseStudent::create([
					"course_id" => $course->id,
					"student_id" => $student->id,
					"teacher_id" => $request->inp_teacher_name[$index],
					"is_imported" => 1,
					"max_course_session" => $request->inp_max_course_session[$index]
				]);

				ImportedStudent::create([
					"student_id" => $student->id,
					"course_id" => $course->id,
					"last_attendance_count" => $request->inp_last_attendance_count[$index]
				]);

				$topics = Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->get();

				$targetFound = false;
				foreach($topics as $topic){
					foreach($topic->activities as $activity){
						if($activity->id != $request->inp_last_activity_unlocked[$index]){
							Progress::create([
								"student_id" => $student->id,
								"activity_id" => $activity->id,
								"course_id" => $course->id,
								"status" => "unlocked"
							]);
						} else {
							Progress::create([
								"student_id" => $student->id,
								"activity_id" => $activity->id,
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

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to import old student data, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.course.show", $course_id))->with("successImportStudent", "Students data imported successfully!");
	}

	public function normalize_confirmation($student_id){
		return view("roles.admin.student.normalize", [
			"student" => User::findOrFail($student_id),
			"imported" => ImportedStudent::where("student_id", $student_id)->get()
		]);
	}

	public function normalize_proceed(Request $request, $student_id){
		$request->validate([
			"checkbox_values" => ["required", new MinimumOneCheckbox]
		]);

		$student = User::findOrFail($student_id);

		try {
			DB::beginTransaction();

			$imported = ImportedStudent::where("student_id", $student->id)->get();

			$to_be_deleted = [];

			foreach($imported as $index => $imp){
				if($request["checkbox_values"][$index] == "on"){
					CourseStudent::where("student_id", $student->id)->where("course_id", $imp->course_id)->update(["is_imported" => 0]);

					array_push($to_be_deleted, $imp->id);
				}
			}

			foreach($to_be_deleted as $del){
				ImportedStudent::destroy($del);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to normalize_student, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.student.show", $student->id))->with("successNormalize", "Successfully normalized the student");
	}

}
