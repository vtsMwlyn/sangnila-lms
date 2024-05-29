<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAccountController extends Controller {
	// Shows all available accounts in Sangnila LMS
	public function index() {
		$accounts = User::where("status", "enabled")/*->paginate(5)*/->get();
		$disabled = User::where("status", "disabled")/*->paginate(5)*/->get();

		return view('roles.admin.account.index', [
			'accounts' => $accounts,
			'disabled' => $disabled
		]);
	}

	// Shows selected account details
	public function show_acc($user_id) {
		$user = User::findOrFail($user_id);

		return view("roles.admin.account.show", [
			"user" => $user
		]);

	}

	// Shows input form for edit an account data
	public function edit_acc($user_id) {
		$user = User::findOrFail($user_id);

		return view("roles.admin.account.edit", [
			"account" => $user,
			"roles" => Role::all()
		]);
	}

	// Update the account in database
	public function update_acc(Request $request, $account_id) {
		$account = User::findOrFail($account_id);

		// $dataToUpdate = $request->except(["_token", "_method"]);

		$dataToUpdate = $request->validate([
			"full_name" => "required|min:3",
			"email" => "required|email:dns",
			"role_id" => "required"
		]);

		User::where("id", $account->id)->update($dataToUpdate);

		return redirect(route("admin.account.show", $account_id))->with("successUpdateAccountData", "Successfully updated account data!");
	}

	// Account disable confirmation page
	public function disable_conf($user_id){
		return view("roles.admin.account.disable", [
			"account" => User::findOrFail($user_id)
		]);
	}

	// Disable account in database
	public function disable_acc($user_id){
		$user = User::findOrFail($user_id);
		User::where("id", $user->id)->update(["status" => "disabled"]);

		return redirect(route("admin.account.index"))->with("successDisableAccount", "Successfully disabled account!");
	}

	// Account enable confirmation page
	public function enable_conf($user_id){
		return view("roles.admin.account.enable", [
			"account" => User::findOrFail($user_id)
		]);
	}

	// Account enable confirmation page
	public function enable_acc($user_id){
		$user = User::findOrFail($user_id);
		User::where("id", $user->id)->update(["status" => "enabled"]);

		return redirect(route("admin.account.index"))->with("successEnableAccount", "Successfully enabled account!");
	}

	// Account deletion confirmation page
	public function delete($account_id){
		$account = User::where('id', $account_id)->first();

		return view('roles.admin.account.destroy', [
			'account' => $account,
		]);
	}

	// Account deletion from database
	public function destroy($user_id) {
		$user = User::findOrFail($user_id);
		User::destroy("id", $user->id);

		return redirect(route("admin.account.index"))->with("successDeleteAccount", "Successfully deleted account!");
	}
}
