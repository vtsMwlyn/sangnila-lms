<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherAccountController extends Controller {
	// ===== ADMIN ===== //
	// List of all active teachers in Sangnila LMS
	public function index() {
		$users = User::where("role_id", 2)->filter(request(["search"]))->get();

		return view('roles.admin.teacher.index', [
			'accounts' => $users,
		]);
	}

	// Shows teacher's details
	public function show($teacher_id) {
		$role = Role::where('role_name', 'Teacher')->first();
		$user = User::where('id', $teacher_id)->where('role_id', $role->id)->first();
		return view('roles.admin.teacher.show', [
			'user' => $user
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

			return back()->with("systemFail", "System failed to edit teacher, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.teacher.show", $teacher_id))->with("successUpdateTeacherData", "Successfully updated teacher data!");
	}

}
