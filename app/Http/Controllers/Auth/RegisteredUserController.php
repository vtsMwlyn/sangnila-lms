<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller {
	public function create() {
		$roles = Role::get();
		return view('auth.register', [
			'roles' => $roles
		]);
	}

	public function admin_create() {
		$roles = Role::get();
		$gender = ["Male (Mr.)", "Female (Ms./Mrs.)"];
		return view('roles.admin.account.create', [
			'roles' => $roles,
			"gender" => $gender
		]);
	}

	public function admin_store(Request $request) {
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
			'password_confirmation' => ['required', 'min:8'],
			'role' => ['required', 'in:Admin,Teacher,Student'],
			"gender" => "required"
		]);

		$role_id = Role::where('role_name', $request->role)->first();
		$user = User::create([
			'full_name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role_id' => $role_id->id,
			"status" => "enabled",
			"email_verified_at" => now() // soon email verification will be enabled
		]);

		UserDetail::create(["user_id" => $user->id, "gender" => $request->gender]);

		return redirect(route('admin.account.index'))->with("successCreateNewAccount", "Successfully created new account!");
	}
}
