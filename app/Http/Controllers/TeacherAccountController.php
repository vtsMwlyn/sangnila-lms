<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherAccountController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$role = Role::where('role_name', 'Teacher')->first();
		$users = $role->users()->get(); // Use get() to retrieve the users
		return view('roles.admin.teacher.index', [
			'accounts' => $users,
		]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($teacher_id) {
		$role = Role::where('role_name', 'Teacher')->first();
		$user = User::where('id', $teacher_id)->where('role_id', $role->id)->first();
		return view('roles.admin.teacher.show', [
			'user' => $user
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	public function admin_edit($teacher_id){
		$teacher = User::findOrFail($teacher_id);

		return view("roles.admin.teacher.edit", [
			"teacher" => $teacher
		]);
	}

	public function admin_update(Request $request, $teacher_id){
		$teacher = User::findOrFail($teacher_id);

		// $dataToUpdate = $request->except(["_token", "_method"]);

		$dataToUpdate = $request->validate([
			"full_name" => "required|min:3",
			"email" => "required|email:dns"
		]);

		User::where("id", $teacher->id)->update($dataToUpdate);

		return redirect(route("admin.teacher.index"))->with("successUpdateTeacherData", "Successfully updated teacher data!");
	}
}
