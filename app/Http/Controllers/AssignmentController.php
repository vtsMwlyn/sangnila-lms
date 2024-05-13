<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\StudentAssignment;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
	//Controlling teacher's role in assignments
    public function teacher_index(){
		return view("roles.teacher.assignment.index");
	}

	public function teacher_show($course_id){
		return view("roles.teacher.assignment.show", [
			"assignments" => StudentAssignment::all(),
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	public function teacher_upload($course_id){
		return view("roles.teacher.assignment.upload", [
			"course" => Course::where("id", $course_id)->first()
		]);
	}

	public function teacher_store(Request $request, $course_id){
		return $request;
	}

	public function teacher_edit($assignment_id){
		return view("roles.teacher.assignment.edit", [
			"assignment" => StudentAssignment::where("id", $assignment_id)->first()
		]);
	}

	public function teacher_update(Request $request, $assignment_id){
		return $request;
	}

	public function teacher_delete($assignment_id){
		return view("roles.teacher.assignment.delete-confirmation");
	}

	public function teacher_destroy($assignment_id){
		return "The assignment will be deleted";
	}


	//Controlling student's role in assignments
	public function student_index(){

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
