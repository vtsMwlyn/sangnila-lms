<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAccountController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$accounts = User::where("status", "enabled")->get();
		$disabled = User::where("status", "disabled")->get();

		return view('roles.sysadmin.account.index', [
			'accounts' => $accounts,
			'disabled' => $disabled
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
	public function show_acc($user_id) {
		$user = User::findOrFail($user_id);

		return view("roles.sysadmin.account.show", [
			"user" => $user
		]);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit_acc($user_id) {
		$user = User::findOrFail($user_id);

		return view("roles.sysadmin.account.edit", [
			"account" => $user
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update_acc(Request $request, $account_id) {
		$account = User::findOrFail($account_id);

		// $dataToUpdate = $request->except(["_token", "_method"]);

		$dataToUpdate = $request->validate([
			"full_name" => "required|min:3",
			"email" => "required|email:dns"
		]);

		User::where("id", $account->id)->update($dataToUpdate);

		return redirect(route("sysadmin.account.index"))->with("successUpdateAccountData", "Successfully updated account data!");
	}

	public function disable_acc($user_id){
		$user = User::findOrFail($user_id);
		User::where("id", $user->id)->update(["status" => "disabled"]);

		return redirect(route("sysadmin.account.index"))->with("successDisableAccount", "Successfully disabled account!");
	}

	public function enable_acc($user_id){
		$user = User::findOrFail($user_id);
		User::where("id", $user->id)->update(["status" => "enabled"]);

		return redirect(route("sysadmin.account.index"))->with("successEnableAccount", "Successfully enabled account!");
	}

	public function delete($account_id){
		$account = User::where('id', $account_id)->first();

		return view('roles.sysadmin.account.destroy', [
			'account' => $account,
		]);
	}

	public function destroy($user_id) {
		$user = User::findOrFail($user_id);
		User::destroy("id", $user->id);

		return redirect(route("sysadmin.account.index"))->with("successDeleteAccount", "Successfully deleted account!");
	}
}
