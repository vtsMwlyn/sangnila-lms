<?php

use App\Models\User;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Activity;
use App\Models\Progress;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Submission;
use App\Models\UserDetail;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\CurriculumTopic;
use App\Models\ImportedStudent;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\CurriculumActivity;
use Illuminate\Support\Facades\Hash;

// Generator functions
function newUser($email, $full_name, $role_id, $gender){
	$user = User::create([
		"full_name" => $full_name,
		"email" => $email,
		"password" => Hash::make(trans("strings.default_password")),
		"email_verified_at" => now(),
		"role_id" => $role_id,
		"status" => "enabled"
	]);

	UserDetail::create(["user_id" => $user->id, "gender" => $gender]);
}

function assignStudent($student_name, $teacher_name, $course_name, $max_session){
	$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();
	$course = Course::where("course_name", $course_name)->first();

	CourseStudent::create([
		"course_id" => $course->id,
		"student_id" => $student->id,
		"teacher_id" => $teacher->id,
		"max_course_session" => $max_session,
		"is_imported" => 0
	]);

	Payment::create([
		"student_id" => $student->id,
		"course_id" => $course->id,
		"number_of_payment" => 1
	]);

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

			Progress::create($newData);
		}
	}
}

function assignTeacher($teacher_name, $courses){
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

	foreach($courses as $c){
		$course = Course::where("course_name", $c)->first();

		CourseTeacher::create([
			"user_id" => $teacher->id,
			"course_id" => $course->id
		]);
	}
}

function addTopicAndActivity($course_name, $topic_name, $activities, $teacher_name){
	$course = Course::where("course_name", $course_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

	$topic = Topic::create(["course_id" => $course->id, "title" => $topic_name, "user_id" => $teacher->id]);

	$n = 0;
	$current_all_topics = $course->topics;

	foreach($current_all_topics as $t){
		foreach($t->activities as $a){
			$n++;
		}
	}

	foreach($activities as $activity){
		Activity::create(["topic_id" => $topic->id, "session" => $n + 1, "title" => $activity, "link" => "https://www.google.com/", "desc" => "This is the description of the activity. It serves as a comprehensive overview, providing a clear explanation or summary of the content. By reading or watching this activity, students will gain a solid understanding of the main concepts and topics covered. They can expect to learn key insights, practical applications, and theoretical foundations that are essential for mastering the subject matter. The description aims to orientate students, helping them to grasp the significance of the activity and its relevance to their learning journey."]);

		$n++;
	}
}

function changeActivityLink($course_name, $topic_name, $activity_name, $new_link){
	$course = Course::where("course_name", $course_name)->first();
	$topic = Topic::where("course_id", $course->id)->where("title", $topic_name)->first();
	$activity = Activity::where("topic_id", $topic->id)->where("title", $activity_name);

	$activity->update(["link" => $new_link]);
}

function generateCurriculum($course_name, $topic_name, $activities){
	$course = Course::where("course_name", $course_name)->first();

	$topic = CurriculumTopic::create(["course_id" => $course->id, "title" => $topic_name]);

	$n = 0;
	$current_all_topics = $course->curriculum_topics;

	foreach($current_all_topics as $t){
		foreach($t->curriculum_activities as $a){
			$n++;
		}
	}

	foreach($activities as $index => $activity){
		CurriculumActivity::create(["curriculum_topic_id" => $topic->id, "session" => $n + 1, "title" => $activity, "link" => "https://www.google.com/", "desc" => "This is the description of the activity. It serves as a comprehensive overview, providing a clear explanation or summary of the content. By reading or watching this activity, students will gain a solid understanding of the main concepts and topics covered. They can expect to learn key insights, practical applications, and theoretical foundations that are essential for mastering the subject matter. The description aims to orientate students, helping them to grasp the significance of the activity and its relevance to their learning journey."]);

		$n++;
	}
}

function importAndAssign($new_student_name, $new_student_email, $gender, $course_name, $teacher_name, $max_course_session, $last_attendance_count){
	$newStudent = User::create([
		"full_name" => $new_student_name,
		"email" => $new_student_email,
		"password" => Hash::make(trans("strings.default_password")),
		"email_verified_at" => now(),
		"role_id" => 3,
		"status" => "enabled"
	]);

	UserDetail::create([
		"user_id" => $newStudent->id,
		"gender" => $gender
	]);

	$course = Course::where("course_name", $course_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

	CourseStudent::create([
		"course_id" => $course->id,
		"teacher_id" => $teacher->id,
		"student_id" => $newStudent->id,
		"max_course_session" => $max_course_session,
		"is_imported" => 1
	]);

	ImportedStudent::create([
		"course_id" => $course->id,
		"student_id" => $newStudent->id,
		"last_attendance_count" => $last_attendance_count
	]);
}

function newAssignment($course_name, $teacher_name, $students, $assignment_title, $deadline_date){
	$course = Course::where("course_name", $course_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

	$newAsg = Assignment::create([
		"teacher_id" => $teacher->id,
		"course_id" => $course->id,
		"title" => $assignment_title,
		"desc" => "This is the description of the assignment.",
		"link" => "https://www.google.com/",
		"deadline_date" => $deadline_date,
		"deadline_time" => "23:59:00"
	]);

	foreach($students as $student_name){
		$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
		StudentAssignment::create([
			"student_id" => $student->id,
			"assignment_id" => $newAsg->id
		]);
	}
}

function newAttendance($course_name, $teacher_name, $students_attended, $attendance_date){
	$course = Course::where("course_name", $course_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

	$newAttendance = Attendance::create([
		"teacher_id" => $teacher->id,
		"course_id" =>  $course->id,
		"attendance_date" => $attendance_date,
		"attendance_identifier" => $course->id . "_" . $teacher->id . "/" . round(microtime(true) * 1000)
	]);

	$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", $teacher->id)->get();

	foreach($course_students as $cs){
		$is_attend = false;
		foreach($students_attended as $sattend){
			if($cs->student->full_name == $sattend){
				$is_attend = true;
				break;
			}
		}

		$newData = [
			"student_id" => $cs->student->id,
			"attendance_id" => $newAttendance->id,
			"activity_progress" => "Ceritanya ini suatu activity"
		];

		if($is_attend){
			$newData["is_attend"] = 1;
			$newData["attendance_detail"] = "Hadir dan telah menyelesaikan materi tertentu pada topic tertentu pada pertemuan kali ini. Kenapa tertentu karena ini adalah fake data yang dibuat pakai seeder biar kelihatan keterangan attendance minimal 30 kata.";
			$newData["learning_status"] = "On Progress";
		} else {
			$newData["is_attend"] = 0;
			$newData["attendance_detail"] = "Student sakit/izin/alfa.";
			$newData["learning_status"] = "Absent";
		}

		StudentAttendance::create($newData);
	}
}

function newSubmissions($course_name, $teacher_name, $student_name, $assignment_title, $submissions){
	$course = Course::where("course_name", $course_name)->first();
	$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();
	$student = User::where("role_id", 3)->where("full_name", $student_name)->first();

	foreach($submissions as $submission){
		$assignment = Assignment::where("course_id", $course->id)->where("teacher_id", $teacher->id)->where("title", $assignment_title)->first();

		Submission::create([
			"student_id" => $student->id,
			"assignment_id" => $assignment->id,
			"link" => "https://www.google.com/",
			"title" => $submission,
			"status" => (now() > $assignment->deadline_date . " " . $assignment->deadline_time)? "Late" : "On Time",
			"feedback" => null
		]);
	}

}
