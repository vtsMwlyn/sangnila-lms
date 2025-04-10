<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\SelfAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller {
	// ===== ADMIN ===== //
	// List of all active teachers in Sangnila LMS
	public function index() {
		$teachers = User::where("role_id", 2)->where('status', 'enabled')->filter(request(["search"]))->orderBy('full_name', 'asc')->get();

		return view('roles.admin.teacher.index', [
			'teachers' => $teachers,
		]);
	}

	// Shows teacher's details
	public function show($teacher_id) {
		$role = Role::where('role_name', 'Teacher')->first();
		$teacher = User::where('id', $teacher_id)->where('role_id', $role->id)->first();
		return view('roles.admin.teacher.show', [
			'teacher' => $teacher
		]);
	}

	// Edit teacher input page
	public function admin_edit($teacher_id){
		$teacher = User::findOrFail($teacher_id);

		return view("roles.admin.teacher.edit", [
			"teacher" => $teacher
		]);
	}

	// Update the teacher data in the database
	public function admin_update(Request $request, $teacher_id){
		$teacher = User::findOrFail($teacher_id);

		$validationRule = [
			"full_name" => "required|min:3",
			"phone_number" => "nullable",
			"city_of_birth" => "nullable|min:3",
			"date_of_birth" => "nullable",
		];

		$validator = Validator::make($request->all(), $validationRule);

        $validator->sometimes('phone_number', ['min:9', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'], function ($input) {
            return true;
        });

		$dataToUpdate = $validator->validate();

		try {
			DB::beginTransaction();

			$teacher->update(["full_name" => $dataToUpdate["full_name"]]);
			unset($dataToUpdate["full_name"]);
			UserDetail::where("user_id", $teacher->id)->update($dataToUpdate);

			DB::commit();

		} catch(Exception $e){
			DB::rollback();

			return back()->with("danger", "System failed to edit teacher, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.teacher.show", $teacher_id))->with("success", "Successfully updated teacher data!");
	}

	// Teacher self attendance
	public function check_in($course_id){
		return view("roles.teacher.check-in", [
			"course" => Course::findOrFail($course_id)
		]);
	}

	public function check_in_store(Request $request, $course_id){
		$validatedData = $request->validate([
			'check_in_time' => 'required',
			'description' => 'required'
		]);

		try {
			$course = Course::findOrFail($course_id);

			if($request->file('image')){
				$validatedData['attendance_evidence'] = $request->file("image")->store("lecturer-checkin");
			}
			else {
				return back()->with('danger', 'Check in requires evidence image. Please allow the usage of the camera then try again, or if the problem persists, please kindly contact our IT team.');
			}

			SelfAttendance::create([
				'course_id' => $course->id,
				'user_id' => Auth::user()->id,
				'self_attendance_date' => Carbon::today()->format('Y-m-d'),
				'check_in_time' => $validatedData['check_in_time'],
				'attendance_evidence' => $validatedData['attendance_evidence'],
				'description' => $validatedData['description']
			]);
		}
		catch(Exception $e){
			if(isset($validatedData['attendance_evidence'])){
				Storage::disk('public')->delete($validatedData['attendance_evidence']);
			}

			return back()->with('danger', 'Cannot sign in due to system error, please contact our IT team. Error detail: ' . $e->getMessage());
		}

		return redirect(route('teacher.attendance.show', $course->id))->with('success', 'Successfully checked in to course ' . $course->course_name . ' at ' . $validatedData['check_in_time'] . ' (GMT+7)');
	}

	public function check_out_store($course_id){
		$course = Course::findOrFail($course_id);

		$currentTime = Carbon::now();
		$checkOutTime = Carbon::parse($currentTime)->format('H:i:s');

		$existingLecturerAtd = SelfAttendance::where('user_id', Auth::user()->id)->where('course_id', $course->id)->where('self_attendance_date', Carbon::parse($currentTime)->format('Y-m-d'))->get();
		$unfinishedSelfAttendance = $existingLecturerAtd->filter(function($item){
			return $item->check_out_time == null;
		})->first();

		if($unfinishedSelfAttendance){
			$unfinishedSelfAttendance->update([
				'check_out_time' => $checkOutTime
			]);
		}
		else {
			return back()->with('danger', 'No attendance data found, probably because you have not checked in yet. If the problem persists please contact our IT team.');
		}

		return redirect(route('teacher.attendance.show', $course->id))->with('success', 'Successfully checked out from course ' . $course->course_name . ' at ' . $checkOutTime . ' (GMT+7)');
	}
}
