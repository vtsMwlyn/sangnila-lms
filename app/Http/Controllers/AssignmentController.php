<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Carbon;
use App\Models\Assignment;
use App\Rules\MinimumOneCheckbox;
use App\Models\Submission;
use App\Models\Role;
use App\Models\StudentAssignment;
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
		$assignments = Assignment::where("teacher_id", Auth::user()->id)->where("course_id", $course_id)->get();

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

		$newAsg = Assignment::create([
			"title" => $validatedData["title"],
			"desc" => $validatedData["desc"],
			"link" => $validatedData["link"],
			"deadline_date" => $validatedData["deadline_date"],
			"deadline_time" => $validatedData["deadline_time"],
			"teacher_id" => Auth::user()->id,
			"course_id" => $course->id,
		]);

		$i = 0;
		foreach($course->students as $student){
			$isAssigned = ($request->checkbox_value[$i] == "on")? 1 : 0;

			if($isAssigned){
				StudentAssignment::create([
					"student_id" => $student->id,
					"assignment_id" => $newAsg->id
				]);
			}

			$i++;
		}

		return redirect(route("teacher.assignment.show", $course_id))->with("successUploadAssignment", "New assignment uploaded successfully!");
	}

	// Edit assignment data input form page
	public function teacher_edit($assignment_id){
		$target_asg = Assignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $target_asg->course_id)->first();
		$students_assigned = StudentAssignment::where("assignment_id", $target_asg->id)->get();

		$student_assignment_status = [];
		foreach($course->students as $index => $student){
			$is_assigned = false;
			foreach($students_assigned as $sa){
				if($sa->student_id == $student->id){
					$is_assigned = true;
					break;
				}
			}

			if($is_assigned){
				$student_assignment_status[$index] = "on";
			} else {
				$student_assignment_status[$index] = "off";
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
		$existingStudentAssignments = StudentAssignment::where("assignment_id", $target_asg->id)->get();

		Assignment::where("id", $target_asg->id)->update([
			"title" => $validatedData["title"],
			"desc" => $validatedData["desc"],
			"link" => $validatedData["link"],
			"deadline_date" => $validatedData["deadline_date"],
			"deadline_time" => $validatedData["deadline_time"],
		]);

		foreach($course->students as $index => $student){
			$isAssigned = ($request->checkbox_value[$index] == "on")? 1 : 0;

			if($isAssigned){
				$exists = false;
				foreach($existingStudentAssignments as $esa){
					if($esa->student_id == $student->id){
						$exists = true;
						break;
					}
				}

				if(!$exists){
					StudentAssignment::create([
						"student_id" => $student->id,
						"assignment_id" => $target_asg->id
					]);
				}
			}
			else {
				$target_del = StudentAssignment::where("student_id", $student->id)->first();
				StudentAssignment::destroy($target_del->id);
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
		$del = Assignment::where("id", $assignment_id)->first();
		$course = Course::where("id", $del->course_id)->first();

		Assignment::destroy($del->id);

		return redirect(route("teacher.assignment.show", $course->id))->with("successDeleteAssignment", "Assignment deleted successfully!");
	}

	// Check submissions from students in an assignment
	public function teacher_check_submission($assignment_id){
		$assignment = Assignment::where("id", $assignment_id)->first();
		$course = $assignment->course;

		$student_assignments = StudentAssignment::where("assignment_id", $assignment_id)->get();

		$nosubmissions = true;
		foreach($student_assignments as $sa){
			if($sa->submissions->count()){
				$nosubmissions = false;
				break;
			}
		}

		return view("roles.teacher.assignment.check-submission", [
			"student_assignments" => $student_assignments,
			"assignment" => $assignment,
			"students" => $course->students,
			"nosubmissions" => $nosubmissions
		]);
	}

	// Check submission history from a student
	public function teacher_check_history($student_assignment_id, $student_id){
		$student_assignment = StudentAssignment::where("id", $student_assignment_id)->first();
		$submissions = $student_assignment->submissions;
		$assignment = $student_assignment->assignment;
		$student = User::where("id", $student_id)->first();

		return view("roles.teacher.assignment.submission-history", [
			"history" => $submissions,
			"assignment" => $assignment,
			"student" => $student
		]);
	}

	// Save feedback added to a submission
	public function teacher_feedback(Request $request, $submission_id, $student_id){
		$asgsmt = Submission::where("id", $submission_id)->first();
		$msg = "Feedback added successfully!";
		if($asgsmt->feedback){
			$msg = "Feedback edited successfully!";
		}

		Submission::where("id", $submission_id)->update(["feedback" => $request->feedback]);

		return redirect(route("teacher.assignment.submission-history", [$asgsmt->student_assignment->id, $student_id]))->with("successModifFeedback", $msg);
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
		$assignments = StudentAssignment::where("student_id", Auth::user()->id)->latest()->get();
		return view("roles.student.assignment.show", [
			"assignments" => $assignments,
			"course" => $course
		]);
	}

	// Submission input form page
	public function student_submit($course_id, $assignment_id){
		$asg = Assignment::where("id", $assignment_id)->first();

		$stdasg = StudentAssignment::where("student_id", Auth::user()->id)->where("assignment_id", $asg->id)->first();

		if($stdasg->submissions->count() == 10){
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
		$student_assignment = StudentAssignment::where("assignment_id", $assignment->id)->where("student_id", Auth::user()->id)->first();

		//The time is currently set to Asia/Jakarta
		$submissionTime = now();
		$deadlineTime = $assignment->deadline_date . " " . $assignment->deadline_time;

		if($submissionTime > $deadlineTime){
			$status = "Late";
		} else {
			$status = "On Time";
		}

		Submission::create([
			"link" => $request->link,
			"title" => $request->title,
			"student_assignment_id" => $student_assignment->id,
			"status" => $status,
		]);

		return redirect(route("student.assignment.show", $course_id))->with("successSubmitAssignment", "Assignment submitted successfully!");
	}

	// Shows submission history in an assignment
	public function student_submission_detail($course_id, $student_assignment_id){
		$student_assignment = StudentAssignment::where("id", $student_assignment_id)->first();
		$submissions = $student_assignment->submissions;
		$assignment = $student_assignment->assignment;

		return view("roles.student.assignment.submission-detail", [
			"course" => Course::where("id", $course_id)->first(),
			"submissions" => $submissions,
			"assignment" => $assignment
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
