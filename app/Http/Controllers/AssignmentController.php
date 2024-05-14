<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use Illuminate\Http\Request;
use App\Models\StudentAssignment;
use App\Rules\MinimumOneCheckbox;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\MockObject\Builder\Stub;

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


	//Controlling student's role in assignments
	public function student_index(){
		return view("roles.student.assignment.index", [
			"courses" => CourseStudent::where("user_id", Auth::user()->id)->get()
		]);
	}

	public function student_show($course_id){
		return view("roles.student.assignment.show", [
			"assignments" => StudentAssignment::where("course_id", $course_id)->where("student_id", Auth::user()->id)->get()
		]);
	}

	public function student_upload(){

	}

	public function student_store(){

	}

	public function student_edit(){

	}

	public function student_update(){

	}
}
