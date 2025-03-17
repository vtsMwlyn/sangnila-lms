<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
	public function student_pay(){
		return view("roles.student.pay");
	}

    public function student_pay_proceed(Request $request){
		$request->validate([
			"course" => "required",
			"session_extend" => "required|numeric|min:8"
		]);

		$cs = CourseStudent::where("student_id", Auth::user()->id)->where("course_id", $request->course);
		$cs->update(["max_course_session" => $cs->first()->max_course_session + $request->session_extend]);

		return redirect(route("profile.show"))->with("success", "Your payment was success!");
	}
}
