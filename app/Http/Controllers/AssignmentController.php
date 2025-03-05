<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Carbon;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Return_;
use App\Models\StudentAssignment;
use App\Rules\MinimumOneCheckbox;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
			"course" => Course::findOrFail($course_id)
		]);
	}

	// New assignment input form page
	public function teacher_upload($course_id){
		$course = Course::findOrFail($course_id);
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

		return view("roles.teacher.assignment.upload", [
			"course_students" => $course_students,
			"course" => $course
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

		$course = Course::findOrFail($course_id);

		try {
			DB::beginTransaction();

			$newAsg = Assignment::create([
				"title" => $validatedData["title"],
				"desc" => $validatedData["desc"],
				"link" => $validatedData["link"],
				"deadline_date" => $validatedData["deadline_date"],
				"deadline_time" => $validatedData["deadline_time"],
				"teacher_id" => Auth::user()->id,
				"course_id" => $course->id,
			]);

			$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();

			foreach($course_students as $i => $cs){
				$isAssigned = ($request->checkbox_value[$i] == "on")? 1 : 0;

				if($isAssigned){
					StudentAssignment::create([
						"student_id" => $cs->student->id,
						"assignment_id" => $newAsg->id
					]);

					Notification::create([
						"user_id" => $cs->student->id,
						"status" => "unread",
						"message" => ((Auth::user()->details->gender == 1)? "Mr. " : "Ms. ") . Auth::user()->full_name . " has uploaded a new assignment \"" . $newAsg->title . "\" in course " . $course->course_name . ". Please do the assignment and submit before " . $newAsg->deadline_date . " at " . $newAsg->deadline_time . "."
					]);
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to create assignment, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.assignment.show", $course_id))->with("successUploadAssignment", "New assignment uploaded successfully!");
	}

	// Edit assignment data input form page
	public function teacher_edit($assignment_id){
		$target_asg = Assignment::findOrFail($assignment_id);
		$course = Course::findOrFail($target_asg->course_id);
		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();
		$students_assigned = StudentAssignment::where("assignment_id", $target_asg->id)->get();

		$student_assignment_status = [];
		foreach($course_students as $index => $cs){
			$is_assigned = false;
			foreach($students_assigned as $sa){
				if($sa->student_id == $cs->student->id){
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
			"course_students" => $course_students,
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

		$target_asg = Assignment::findOrFail($assignment_id);
		$course = Course::findOrFail($target_asg->course_id);

		try {
			DB::beginTransaction();

			$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", Auth::user()->id)->get();
			$existingStudentAssignments = StudentAssignment::where("assignment_id", $target_asg->id)->get();

			$target_asg->update([
				"title" => $validatedData["title"],
				"desc" => $validatedData["desc"],
				"link" => $validatedData["link"],
				"deadline_date" => $validatedData["deadline_date"],
				"deadline_time" => $validatedData["deadline_time"],
			]);

			foreach($course_students as $index => $cs){
				$isAssigned = ($request->checkbox_value[$index] == "on")? 1 : 0;

				$exists = false;
				foreach($existingStudentAssignments as $esa){
					if($esa->student_id == $cs->student->id){
						$exists = true;
						break;
					}
				}

				if($isAssigned){
					if(!$exists){
						StudentAssignment::create([
							"student_id" => $cs->student->id,
							"assignment_id" => $target_asg->id
						]);
					}
				}
				else {
					if($exists){
						$target_del = StudentAssignment::where("student_id", $cs->student->id)->where("assignment_id", $assignment_id)->first();
						StudentAssignment::destroy($target_del->id);
					}
				}
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to edit assignment, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.assignment.show", $course->id))->with("successEditAssignment", "Assignment edited successfully!");

	}

	// Assignment deletion confirmation
	public function teacher_delete($assignment_id){
		$asg = Assignment::findOrFail($assignment_id);
		return view("roles.teacher.assignment.delete-confirmation", [
			"assignment" => $asg,
			"course" => Course::findOrFail($asg->course_id)
		]);
	}

	// Delete assignment data from database
	public function teacher_destroy($assignment_id){
		$del = Assignment::findOrFail($assignment_id);
		$course = Course::findOrFail($del->course_id);

		$del->delete();

		return redirect(route("teacher.assignment.show", $course->id))->with("successDeleteAssignment", "Assignment deleted successfully!");
	}

	// Check submissions from students in an assignment
	public function teacher_check_submission($assignment_id){
		$assignment = Assignment::findOrFail($assignment_id);
		$submissions = Submission::where("assignment_id", $assignment->id)->get();

		$student_list = [];
		foreach($submissions as $index => $sbm){
			if($index == 0){
				array_push($student_list, $sbm->student);
				continue;
			}

			$alreadyIn = false;
			foreach($student_list as $sl){
				if($sbm->student->id == $sl->id){
					$alreadyIn = true;
					break;
				}
			}

			if(!$alreadyIn){
				array_push($student_list, $sbm->student);
			}
		}


		$latest_submissions = [];
		foreach($student_list as $student){
			$submissions = $student->submissions->where("assignment_id", $assignment->id)->values()->all(); //biar jadi array kalo ngga dia bentuknya {{...}, {...}, ...}
			$n = count($submissions);
			if($n > 0){
				array_push($latest_submissions, $submissions[$n - 1]);
			}
		}

		return view("roles.teacher.assignment.check-submission", [
			"latest_submissions" => $latest_submissions,
			"assignment" => $assignment,
		]);
	}

	// Check submission history from a student
	public function teacher_check_history($assignment_id, $student_id){
		$student = User::findOrFail($student_id);
		$assignment = Assignment::findOrFail($assignment_id);

		return view("roles.teacher.assignment.submission-history", [
			"history" => $student->submissions->where("assignment_id", $assignment_id)->values()->all(),
			"assignment" => $assignment,
			"student" => $student
		]);
	}

	// Save feedback added to a submission
	public function teacher_feedback(Request $request, $submission_id, $student_id){
		$asgsmt = Submission::findOrFail($submission_id);

		try {
			DB::beginTransaction();

			$msg = "Feedback added successfully!";
			if($asgsmt->feedback){
				$msg = "Feedback edited successfully!";
			}

			$asgsmt->update(["feedback" => $request->feedback]);

			Notification::create([
				"user_id" => $asgsmt->student->id,
				"status" => "unread",
				"message" => ((Auth::user()->details->gender == 1)? "Mr. " : "Ms. ") . Auth::user()->full_name . " has commented on your submission \"" . $asgsmt->title . "\" in assignment \"" . $asgsmt->assignment->title . "\" in course " . $asgsmt->assignment->course->course_name . "."
			]);

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to add/edit feedback, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("teacher.assignment.submission-history", [$asgsmt->assignment->id, $student_id]))->with("successModifFeedback", $msg);
	}


	// ===== STUDENT ===== //
	// Shows list of enrolled course to select before continue
	public function student_index(){
		$course_students = CourseStudent::where("student_id", Auth::user()->id)->get();
		$assignments_data = [];

		foreach($course_students as $cs){
			$ad = [];
			$ad["course_student"] = $cs;

			$total_assignments = 0;
			$assignments_done = 0;
			$nd = [];

			$student_assignments = StudentAssignment::where("student_id", Auth::user()->id)->get();
			foreach($student_assignments as $sa){
				if($sa->assignment->course_id == $cs->course_id){
					$total_assignments++;

					if($sa->assignment->deadline_date >= Carbon::today()->format('Y-m-d')){
						array_push($nd, $sa->assignment->deadline_date . " " . $sa->assignment->deadline_time);
					}

					foreach($sa->assignment->submissions as $submission){
						if($submission->student_id == Auth::user()->id && $submission->assignment_id == $sa->assignment->id){
							$assignments_done++;
							break;
						}
					}
				}
			}

			$assignments_pending = $total_assignments - $assignments_done;

			$ad["status"] = [
				"total" => $total_assignments,
				"done" => $assignments_done,
				"pending" => $assignments_pending,
				"nearest_deadline" => (count($nd) > 0)? min($nd) : "N/A"
			];

			array_push($assignments_data, $ad);
		}

		return view("roles.student.assignment.index", [
			"assignments_data" => $assignments_data
		]);
	}

	// List of assignments given to the student
	public function student_show($course_id){
		$course = Course::findOrFail($course_id);
		$student_assignments = StudentAssignment::where("student_id", Auth::user()->id)->latest()->get();

		$assignments_assigned = [];
		$submissions_per_assignment = [];
		foreach($student_assignments as $sa){
			$spa = [];
			if($sa->assignment->course_id == $course_id){
				array_push($assignments_assigned, $sa->assignment);
				$submissions = $sa->assignment->submissions;

				foreach($submissions as $sbm){
					if($sbm->student_id == Auth::user()->id && $sbm->assignment_id == $sa->assignment->id){
						array_push($spa, $sbm);
					}
				}

				array_push($submissions_per_assignment, $spa);

			}
		}

		return view("roles.student.assignment.show", [
			"assignments" => $assignments_assigned,
			"submissions_per_assignment" => $submissions_per_assignment,
			"course" => $course
		]);
	}

	// Submission input form page
	public function student_submit($course_id, $assignment_id){
		$asg = Assignment::findOrFail($assignment_id);

		$n = 0;
		foreach($asg->submissions as $sbm){
			if($sbm->student_id == Auth::user()->id){
				$n++;
			}
		}

		if($n == 10){
			return back()->with("maximumSubmission", "Sorry, your assignment submission is already in its limit!");
		}

		return view("roles.student.assignment.submit", [
			"course" => Course::findOrFail($course_id),
			"assignment" => $asg
		]);
	}

	// Insert new submission data into database
	public function student_store(Request $request, $course_id, $assignment_id){
		$request->validate([
			"link" => "required|url",
			"title" => "required|min:3"
		]);

		$assignment = Assignment::findOrFail($assignment_id);

		try {
			DB::beginTransaction();

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
				"student_id" => Auth::user()->id,
				"assignment_id" => $assignment->id,
				"status" => $status,
			]);

			Notification::create([
				"user_id" => $assignment->teacher_id,
				"status" => "unread",
				"message" => Auth::user()->full_name . " has made new submission on assignment \"" . $assignment->title . "\" in course " . $assignment->course->course_name
			]);

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			return back()->with("systemFail", "System failed to upload your assignment submission, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("student.assignment.show", $course_id))->with("successSubmitAssignment", "Assignment submitted successfully!");
	}

	// Shows submission history in an assignment
	public function student_submission_detail($course_id, $student_id, $assignment_id){
		$assignment = Assignment::findOrFail($assignment_id);
		$submissions = Submission::where("assignment_id", $assignment->id)->where("student_id", $student_id)->get();

		return view("roles.student.assignment.submission-detail", [
			"course" => Course::findOrFail($course_id),
			"submissions" => $submissions,
			"assignment" => $assignment
		]);
	}


	// ===== ADMIN ===== //
	// Showing selected student's assignment data in all course enrolled
	public function admin_show($student_id, $course_id){
		$student = User::findOrFail($student_id);
		$student_assignments = StudentAssignment::where("student_id", $student_id)->get();
		$course = Course::findOrFail($course_id);

		$assignments = [];
		foreach($student_assignments as $sa){
			if($sa->assignment->course_id == $course_id){
				array_push($assignments, $sa->assignment);
			}
		}

		return view("roles.admin.student.asg-details", [
			"assignments" => $assignments,
			"student" => $student,
			"course" =>	$course
		]);
	}
}
