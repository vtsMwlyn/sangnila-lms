<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller {
	/**
	 * Display the registration view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create() {
		$roles = Role::get();
		return view('auth.register', [
			'roles' => $roles
		]);
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request) {
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'role' => ['required', 'in:SysAdmin, Admin,Teacher,Student'], // Use the 'in' rule
		]);
		$role_id = Role::where('role_name', $request->role)->first();
		$user = User::create([
			'full_name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role_id' => $role_id->id,
		]);

		event(new Registered($user));

		Auth::login($user);

		return redirect(RouteServiceProvider::HOME);
	}

	// ========== SYSADMIN ==========
	/**
	 * Display the registration view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function sys_create() {
		$roles = Role::get();
		return view('roles.sysadmin.account.create', [
			'roles' => $roles
		]);
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function sys_store(Request $request) {
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'role' => ['required', 'in:Admin,Teacher,Student'], // Exclude sysadmin
		]);

		$role_id = Role::where('role_name', $request->role)->first();
		$user = User::create([
			'full_name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role_id' => $role_id->id,
		]);

		return redirect(route('sysadmin.account.index'));
	}

	// ========== ADMIN ==========
	/**
	 * Display the registration view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function adm_create() {
		$roles = Role::get();
		return view('roles.sysadmin.account.create', [
			'roles' => $roles
		]);
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function adm_store(Request $request) {
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'role' => ['required', 'in:Admin,Teacher,Studen'], // Exclude sysadmin
		]);

		$role_id = Role::where('role_name', $request->role)->first();
		$user = User::create([
			'full_name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role_id' => $role_id->id,
		]);

		return redirect(route('sysadmin.account.index'));
	}
}
