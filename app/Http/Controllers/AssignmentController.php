<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Carbon;
use App\Models\Assignment;
use App\Rules\MinimumOneCheckbox;
use App\Models\AssignmentSubmission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class AssignmentController extends Controller
{
	// ===== TEACHER ===== //
	// Shows the teacher list of courses assigned to select first before continue
    public function teacher_index(){
		return view("roles.teacher.assignment.index");
	}

	// List of all assignments in the selected course
	public function teacher_show($course_id){
		$all_asg_data = Assignment::where("course_id", $course_id)->get();

		$assignments = [];

		foreach($all_asg_data as $index => $asg_data){
			if($index == 0){
				array_push($assignments, $asg_data);
			}
			else {
				$titleAlreadyExists = false;
				foreach($assignments as $asg){
					if($asg_data->title == $asg->title){
						$titleAlreadyExists = true;
						break;
					}
				}

				if(!$titleAlreadyExists){
					array_push($assignments, $asg_data);
				}
			}
		}

		return view("roles.teacher.assignment.show", [
			"assignments" => $assignments,
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	// New assignment input form page
	public function teacher_upload($course_id){
		return view("roles.teacher.assignment.upload", [
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	// Store new assignment data into database
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

			Assignment::create([
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

	// Edit assignment data input form page
	public function teacher_edit($assignment_id){
		$target_asg = Assignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $target_asg->course_id)->first();
		$all_asg = Assignment::where("title", $target_asg->title)->where("course_id", $course->id)->get();

		$student_assignment_status = [];
		// If the number students still the same within the record, then just check the student assignment status
		if($all_asg->count() == $course->students->count()){
			foreach($all_asg as $assignment){
				if($assignment->student_is_assigned == 1){
					array_push($student_assignment_status, "on");
				}
				else {
					array_push($student_assignment_status, "off");
				}
			}
		}

		// If the number of student is increasing then should be checked first whether the student data is already in record or not, if it is already in the record then just check from the database its assignment status, but if it doesnt exist then give it default checkbox value off
		else {
			foreach($course->students as $std){
				$studentExists = false;
				foreach($all_asg as $assignment){
					if($std->id == $assignment->student_id){
						$studentExists = true;
						break;
					}
				}

				if($studentExists){
					if($assignment->student_is_assigned == 1){
						array_push($student_assignment_status, "on");
					}
					else {
						array_push($student_assignment_status, "off");
					}
				}
				else {
					array_push($student_assignment_status, "off");
				}
			}
		}

		return view("roles.teacher.assignment.edit", [
			"assignment" => $target_asg,
			"course" => $course,
			"checkboxes_values" => $student_assignment_status
		]);
	}

	// Update the assignment data in the database
	public function teacher_update(Request $request, $assignment_id){
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"desc" => "required|min:3",
			"link" => "required|url",
			"deadline_date" => "required",
			"deadline_time" => "required",
			"checkbox_value" => ["required", new MinimumOneCheckbox]
		]);

		$target_asg = Assignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $target_asg->course_id)->first();
		$existingAssignmentData = Assignment::where("title", $target_asg->title)->where("course_id", $course->id)->get();

		if($existingAssignmentData->count() == $course->students->count()){
			$i = 0;
			foreach($existingAssignmentData as $a){
				$isAssigned = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;
				// If the number of the students in the course and in the assignment record is still the same, just update the data
					Assignment::where("id", $a->id)->update([
						"title" => $validatedData["title"],
						"desc" => $validatedData["desc"],
						"link" => $validatedData["link"],
						"deadline_date" => $validatedData["deadline_date"],
						"deadline_time" => $validatedData["deadline_time"],
						"student_is_assigned" => $isAssigned
					]);
				$i++;
			}
		}

		// But if the number of students is increasing, if the student hasn't been in the assignment record yet, we have to add them into the record
		else {
			$i = 0;
			foreach($course->students as $s){
				$isAssigned = ($validatedData["checkbox_value"][$i] == "on")? 1 : 0;

				$studentIsFound = false;
				foreach($existingAssignmentData as $a){
					if($s->id == $a->student_id){
						$studentIsFound = true;
						break;
					}
				}

				if(!$studentIsFound){
					Assignment::create([
						"title" => $validatedData["title"],
						"desc" => $validatedData["desc"],
						"link" => $validatedData["link"],
						"deadline_date" => $validatedData["deadline_date"],
						"deadline_time" => $validatedData["deadline_time"],
						"student_is_assigned" => $isAssigned,
						"teacher_id" => Auth::user()->id,
						"course_id" => $course->id,
						"student_id" => $s->id
					]);
				}
				else {
					Assignment::where("id", $a->id)->update([
						"title" => $validatedData["title"],
						"desc" => $validatedData["desc"],
						"link" => $validatedData["link"],
						"deadline_date" => $validatedData["deadline_date"],
						"deadline_time" => $validatedData["deadline_time"],
						"student_is_assigned" => $isAssigned
					]);
				}

				$i++;
			}
		}

		return redirect(route("teacher.assignment.show", $course->id))->with("successEditAssignment", "Assignment edited successfully!");

	}

	// Assignment deletion confirmation
	public function teacher_delete($assignment_id){
		$asg = Assignment::where("id", $assignment_id)->first();
		return view("roles.teacher.assignment.delete-confirmation", [
			"assignment" => $asg,
			"course" => Course::where("id", $asg->course_id)->first()
		]);
	}

	// Delete assignment data from database
	public function teacher_destroy($assignment_id){
		$asg = Assignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $asg->course_id)->first();
		$del_asg = Assignment::where("title", $asg->title)->get();

		foreach($del_asg as $del){
			Assignment::destroy("id", $del->id);
		}

		return redirect(route("teacher.assignment.show", $course->id))->with("successDeleteAssignment", "Assignment deleted successfully!");
	}

	// Check submissions from students in an assignment
	public function teacher_check_submission($assignment_id){
		$assignment = Assignment::where("id", $assignment_id)->first();
		$course = $assignment->course;

		$latest_submission = [];
		foreach($course->students as $student){
			if($student->status == "disabled"){
				continue;
			}
			// $student_submissions = AssignmentSubmission::where("student_id", $student->id)->where("assignment_id", $assignment->id)->latest()->get();
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

	// Check submission history from a student
	public function teacher_check_history($submission_id, $student_id){
		$submission = AssignmentSubmission::where("id", $submission_id)->first();
		$assignment = $submission->assignment;
		$student = User::where("id", $student_id)->first();

		// $students_submission = $student->assignment_submissions;

		$students_submission = AssignmentSubmission::where("student_id", $student_id)->where("assignment_id", $assignment->id)->get();

		$history = [];

		for($i = $students_submission->count() - 1; $i >= 0; $i--){
			// if($students_submission[$i]->assignment->title == $assignment->title){
			if($students_submission[$i]->assignment->course_id == $assignment->course_id && $students_submission[$i]->assignment->title == $assignment->title){
				array_push($history, $students_submission[$i]);
			}
		}

		return view("roles.teacher.assignment.submission-history", [
			"history" => $history,
			"assignment" => $assignment,
			"student" => $student
		]);
	}

	// Save feedback added to a submission
	public function teacher_feedback(Request $request, $submission_id, $student_id){
		$asgsmt = AssignmentSubmission::where("id", $submission_id)->first();
		$msg = "Feedback added successfully!";
		if($asgsmt->feedback){
			$msg = "Feedback edited successfully!";
		}

		AssignmentSubmission::where("id", $submission_id)->update(["feedback" => $request->feedback]);

		return redirect(route("teacher.assignment.submission-history", [$submission_id, $student_id]))->with("successModifFeedback", $msg);
	}


	// ===== STUDENT ===== //
	// Shows list of enrolled course to select before continue
	public function student_index(){
		return view("roles.student.assignment.index", [
			"courseStudents" => CourseStudent::where("user_id", Auth::user()->id)->get()
		]);
	}

	// List of assignments given to the student
	public function student_show($course_id){
		$course = Course::where("id", $course_id)->first();
		return view("roles.student.assignment.show", [
			"assignments" => Assignment::where("course_id", $course_id)->where("student_id", Auth::user()->id)->where("student_is_assigned", 1)->latest()->get(),
			"course" => $course
		]);
	}

	// Submission input form page
	public function student_submit($course_id, $assignment_id){
		$asg = Assignment::where("id", $assignment_id)->first();
		if($asg->submissions->count() == 10){
			return back()->with("maximumSubmission", "Sorry, your assignment submission is already in its limit!");
		}

		return view("roles.student.assignment.submit", [
			"course" => Course::where("id", $course_id)->first(),
			"assignment" => $asg
		]);
	}

	// Insert new submission data into database
	public function student_store(Request $request, $course_id, $assignment_id){
		$request->validate([
			"link" => "required|url",
			"title" => "required|min:3"
		]);

		$assignment = Assignment::where("id", $assignment_id)->first();

		//The time is currently set to Asia/Jakarta
		$submissionTime = now();
		$deadlineTime = $assignment->deadline_date . " " . $assignment->deadline_time;

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

	// Shows submission history in an assignment
	public function student_submission_detail($course_id, $assignment_id){
		return view("roles.student.assignment.submission-detail", [
			"course" => Course::where("id", $course_id)->first(),
			"assignment" => Assignment::where("id", $assignment_id)->first()
		]);
	}


	// ===== ADMIN ===== //
	// Showing selected student's attendance data in all course enrolled
	public function admin_show($student_id, $course_id){
		$assignments = Assignment::where("course_id", $course_id)->where("student_id", $student_id)->where("student_is_assigned", 1)->get();

		return view("roles.admin.student.asg-details", [
			"assignments" => $assignments,
			"student" => User::where("id", $student_id)->first(),
			"course" => Course::where("id", $course_id)->first()
		]);
	}
}
