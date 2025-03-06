<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Exception;
use Carbon\Carbon;
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
use App\Models\SelfAttendance;
use App\Models\StudentAttendance;
use Google\Service\ServiceUsage\Impact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
			"teacher" => "required",
			"max_course_session" => "required|numeric|min:1",
			'learning_status' => 'required'
		]);

		try {
			DB::beginTransaction();

			$course = Course::findorFail($validatedData["course"]);
			$teacher = User::findOrFail($validatedData["teacher"]);
			$student = User::findOrFail($student_id);

			CourseStudent::create([
				'student_id' => $student_id,
				'course_id' => $course->id,
				'max_course_session' => $validatedData["max_course_session"],
				'learning_status' => $validatedData['learning_status'], 
				"teacher_id" => $teacher->id,
				"is_imported" => 0,
			]);

			$existingProgress = Progress::where('student_id', $student->id)
				->where('course_id', $course->id)
				->pluck('activity_id')
				->toArray();

			$topics = Topic::where("course_id", $course->id)->where("user_id", $teacher->id)->get();

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

	// Edit student assignment data
	public function update(Request $request, $course_student_id){
		$validatedData = $request->validate([
			"max_course_session" => "required|numeric|min:1",
			'learning_status' => 'required',
			'teacher' => 'required',
		]);

		try {
			DB::beginTransaction();

			$course_student = CourseStudent::findOrFail($course_student_id);
			$course = $course_student->course;
			$student = $course_student->student;
			$teacher = $course_student->teacher;
			
			$course_student->update([
				"max_course_session" => $validatedData['max_course_session'],
				'learning_status' => $validatedData['learning_status'],
				'teacher_id' => $validatedData['teacher'],
			]);

			// Remove old progress
			Progress::where('student_id', $course_student->student->id)->where('course_id', $course_student->course->id)->delete();

			// Regenerate progress
			$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
				return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
			})->orderBy('session', 'asc')->get();

			$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
				return $query->where('course_id', $course->id);
			})->get();

			$session_counter = 1;

			foreach($activities as $index => $activity){
				if($activity->session != $session_counter){
					$session_counter++;
				}

				Progress::updateOrCreate([
					"student_id" => $student->id,
					"course_id" => $course->id,
					"activity_id" => $activity->id,
				],
				[
					"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with('errorEditCourseStudent', 'System failed to edit the course student data. Please report to our IT team, error detail: ' . $e->getMessage());
		}

		return redirect(route('admin.student.show', $course_student->student_id))->with("successEditAssignInfo", "Successfully edited the student assignment info!");
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
			Progress::where('student_id', $student_id)->where('course_id', $course_id)->delete();
			CourseStudent::where('student_id', $student_id)->where('course_id', $course_id)->delete();
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to unassign the course from the student, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route('admin.student.show', $student_id))->with("successUnassignFromCourse", "Successfully unassigned the student from the course!");;
	}

	// Batch assign student to course
	public function batch_assign_student($course_id){
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

		return view("roles.admin.student.batch-assign-student", [
			"course" => $course,
			"allStudents" => $filtered,
			"allTeachers" => $course->teachers
		]);
	}

	public function batch_assign_student_store(Request $request, $course_id){
		$request->validate([
			'studentName' => 'required'
		]);

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
		// return $request;
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

				// return $student;

				$teacher_id = $request->inp_teacher_name[$index];

				CourseStudent::create([
					"course_id" => $course->id,
					"student_id" => $student->id,
					"teacher_id" => $teacher_id,
					"is_imported" => 1,
					"max_course_session" => $request->inp_max_course_session[$index]
				]);

				ImportedStudent::create([
					"student_id" => $student->id,
					"course_id" => $course->id,
					"last_attendance_count" => $request->inp_last_attendance_count[$index]
				]);

				// return $request->inp_last_activity_unlocked[$index];
				$activity_id = $request->inp_last_activity_unlocked[$index];
				$targetLastActivity = Activity::findOrFail($activity_id);

				$existingProgress = Progress::where('student_id', $student->id)
					->where('course_id', $course->id)
					->pluck('activity_id')
					->toArray();

				$activities = Activity::whereHas('topic', function($query) use ($course, $teacher_id){
					return $query->where('course_id', $course->id)->where('user_id', $teacher_id);
				})->orderBy('session', 'asc')->get();

				$found = false;

				foreach($activities as $activity){
					if(!in_array($activity->id, $existingProgress)){
						Progress::create([
							"student_id" => $student->id,
							"activity_id" => $activity->id,
							"course_id" => $course->id,
							"status" => $found? "locked" : 'unlocked'
						]);
						
						
					}
					
					if($activity->id == $targetLastActivity->id){
						$found = true;
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			throw $e;

			return back()->with("systemFail", "System failed to import old student data, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.course.show", $course_id))->with("successImportStudent", "Students data imported successfully!");
	}

	public function imported_data_update(Request $request, $student_id, $course_id){
		$request->validate([
			'last_attendance_count' => 'required|numeric|min:0'
		]);

		$student = User::findOrFail($student_id);
		$course = Course::findOrFail($course_id);

		$importedStudentData = ImportedStudent::where('student_id', $student->id)->where('course_id', $course->id)->first();
		if($importedStudentData){
			$importedStudentData->update([
				'last_attendance_count' => $request->last_attendance_count
			]);
		}

		return redirect(route("admin.student.show", $student->id))->with("successNormalize", "Successfully normalized the student");
	}

	public function normalize_proceed(Request $request, $student_id, $course_id){
		$student = User::findOrFail($student_id);
		$course = Course::findOrFail($course_id);

		$cs = CourseStudent::where('student_id', $student->id)->where('course_id', $course->id)->first();
		$cs->update(['is_imported' => 0]);

		$importedStudentData = ImportedStudent::where('student_id', $student->id)->where('course_id', $course->id)->first();
		if($importedStudentData){
			$importedStudentData->delete();
		}

		return redirect(route("admin.student.show", $student->id))->with("successNormalize", "Successfully normalized the student");
	}
}
