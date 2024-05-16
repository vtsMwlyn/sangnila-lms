<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Carbon;
use App\Models\StudentAssignment;
use App\Rules\MinimumOneCheckbox;
use App\Models\AssignmentSubmission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
	//Controlling teacher's role in assignments
    public function teacher_index(){
		return view("roles.teacher.assignment.index");
	}

	public function teacher_show($course_id){
		return view("roles.teacher.assignment.show", [
			"assignments" => StudentAssignment::where("course_id", $course_id)->get(),
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	public function teacher_upload($course_id){
		return view("roles.teacher.assignment.upload", [
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	public function teacher_store(Request $request, $course_id){
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "required|url",
			"deadline_date" => "required",
			"deadline_time" => "required",
			"checkbox_value" => ["required", new MinimumOneCheckbox]
		]);

		$course = Course::where("id", $course_id)->first();

		$i = 0;
		foreach($course->students as $student){
			$isAssigned = ($request->checkbox_value[$i] == "on")? 1 : 0;

			StudentAssignment::create([
				"title" => $validatedData["title"],
				"desc" => $validatedData["desc"],
				"link" => $validatedData["link"],
				"deadline_date" => $validatedData["deadline_date"],
				"deadline_time" => $validatedData["deadline_time"],
				"student_is_assigned" => $isAssigned,
				"teacher_id" => Auth::user()->id,
				"course_id" => $course->id,
				"student_id" => $student->id
			]);

			$i++;
		}

		return redirect(route("teacher.assignment.show", $course_id))->with("successUploadAssignment", "New assignment uploaded successfully!");
	}

	public function teacher_edit($assignment_id){
		$target_asg = StudentAssignment::where("id", $assignment_id)->first();
		$all_asg = StudentAssignment::where("title", $target_asg->title)->get();
		$course = Course::where("id", $target_asg->course_id)->first();
		$student_assignment_status = [];

		foreach($all_asg as $assignment){
			if($assignment->student_is_assigned){
				array_push($student_assignment_status, "on");
			}
			else {
				array_push($student_assignment_status, "off");
			}
		}

		return view("roles.teacher.assignment.edit", [
			"assignment" => $target_asg,
			"course" => $course,
			"checkboxes_values" => $student_assignment_status
		]);
	}

	public function teacher_update(Request $request, $assignment_id){
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "required|url",
			"deadline_date" => "required",
			"deadline_time" => "required",
			"checkbox_value" => ["required", new MinimumOneCheckbox]
		]);

		$target_asg = StudentAssignment::where("id", $assignment_id)->first();
		$existingAssignmentData = StudentAssignment::where("title", $target_asg->title)->get();
		$course = Course::where("id", $target_asg->course_id)->first();

		$i = 0;
		foreach($existingAssignmentData as $a){
			$isAssigned = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;

			StudentAssignment::where("id", $a->id)->update([
				"title" => $validatedData["title"],
				"desc" => $validatedData["desc"],
				"link" => $validatedData["link"],
				"deadline_date" => $validatedData["deadline_date"],
				"deadline_time" => $validatedData["deadline_time"],
				"student_is_assigned" => $isAssigned,
			]);

			$i++;
		}

		return redirect(route("teacher.assignment.show", $course->id))->with("successEditAssignment", "Assignment edited successfully!");

	}

	public function teacher_delete($assignment_id){
		$asg = StudentAssignment::where("id", $assignment_id)->first();
		return view("roles.teacher.assignment.delete-confirmation", [
			"assignment" => $asg,
			"course" => Course::where("id", $asg->course_id)->first()
		]);
	}

	public function teacher_destroy($assignment_id){
		$asg = StudentAssignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $asg->course_id)->first();
		$del_asg = StudentAssignment::where("title", $asg->title)->get();

		foreach($del_asg as $del){
			StudentAssignment::destroy("id", $del->id);
		}

		return redirect(route("teacher.assignment.show", $course->id))->with("successDeleteAssignment", "Assignment deleted successfully!");
	}

	public function teacher_check_submission($assignment_id){
		$assignment = StudentAssignment::where("id", $assignment_id)->first();
		$course = $assignment->course;

		$latest_submission = [];
		foreach($course->students as $student){
			$student_submissions = AssignmentSubmission::where("student_id", $student->id)->latest()->get();

			foreach($student_submissions as $submission){
				if($submission->assignment->title == $assignment->title){
					array_push($latest_submission, $submission);
					break;
				}
			}
		}

		return view("roles.teacher.assignment.check-submission", [
			"submissions" => $latest_submission,
			"assignment" => $assignment,
			"students" => $course->students
		]);
	}

	public function teacher_check_history($submission_id, $student_id){
		$submission = AssignmentSubmission::where("id", $submission_id)->first();
		$assignment = $submission->assignment;
		$student = User::where("id", $student_id)->first();

		$students_submission = $student->assignment_submissions;
		$history = [];

		for($i = $students_submission->count() - 1; $i >= 0; $i--){
			if($students_submission[$i]->assignment->title == $assignment->title){
				array_push($history, $students_submission[$i]);
			}
		}

		return view("roles.teacher.assignment.submission-history", [
			"history" => $history,
			"assignment" => $assignment,
			"student" => $student
		]);
	}

	public function teacher_feedback(Request $request, $submission_id, $student_id){
		$asgsmt = AssignmentSubmission::where("id", $submission_id)->first();
		$msg = "Feedback added successfully!";
		if($asgsmt->feedback){
			$msg = "Feedback edited successfully!";
		}

		AssignmentSubmission::where("id", $submission_id)->update(["feedback" => $request->feedback]);

		return redirect(route("teacher.assignment.submission-history", [$submission_id, $student_id]))->with("successModifFeedback", $msg);
	}


	//Controlling student's role in assignments
	public function student_index(){
		return view("roles.student.assignment.index", [
			"courseStudents" => CourseStudent::where("user_id", Auth::user()->id)->get()
		]);
	}

	public function student_show($course_id){
		$course = Course::where("id", $course_id)->first();
		return view("roles.student.assignment.show", [
			"assignments" => StudentAssignment::where("course_id", $course_id)->where("student_id", Auth::user()->id)->where("student_is_assigned", 1)->latest()->get(),
			"course" => $course
		]);
	}

	public function student_submit($course_id, $assignment_id){
		$asg = StudentAssignment::where("id", $assignment_id)->first();
		if($asg->submissions->count() == 10){
			return back()->with("maximumSubmission", "Sorry, your assignment submission is already in its limit!");
		}

		return view("roles.student.assignment.submit", [
			"course" => Course::where("id", $course_id)->first(),
			"assignment" => $asg
		]);
	}

	public function student_store(Request $request, $course_id, $assignment_id){
		$request->validate([
			"link" => "required|url",
			"title" => "required|min:3"
		]);

		$assignment = StudentAssignment::where("id", $assignment_id)->first();

		//The time is currently set to Asia/Jakarta
		$submissionTime = Carbon::parse(now());
		$deadlineTime = Carbon::parse($assignment->deadline_date . " " . $assignment->deadline_time);

		if($submissionTime > $deadlineTime){
			$status = "Late";
		} else {
			$status = "On Time";
		}

		AssignmentSubmission::create([
			"link" => $request->link,
			"title" => $request->title,
			"assignment_id" => $assignment_id,
			"status" => $status,
			"student_id" => Auth::user()->id
		]);

		return redirect(route("student.assignment.show", $course_id))->with("successSubmitAssignment", "Assignment submitted successfully!");
	}

	public function student_submission_detail($course_id, $assignment_id){
		return view("roles.student.assignment.submission-detail", [
			"course" => Course::where("id", $course_id)->first(),
			"assignment" => StudentAssignment::where("id", $assignment_id)->first()
		]);
	}
}
