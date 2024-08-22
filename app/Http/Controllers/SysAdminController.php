<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SysAdminController extends Controller
{
    public function sysadmin_login(){
		return view("roles.sysadmin.login");
	}

	public function sysadmin_authenticate(Request $request){
		$cred = $request->validate([
			"email" => "email:dns|required",
			"password" => "required"
		]);

		if(Auth::attempt($cred)){
			$request->session()->regenerate();

			$user = Auth::user();
			User::findOrFail($user->id)->update(["last_login" => Carbon::now()]);

			return redirect()->intended(route('dashboard'));
		}
		else {
			return back()->with("loginFailed", "Fail to login");
		}
	}

	public function sysadmin_logout(){
		Auth::logout();

		request()->session()->invalidate();

		request()->session()->regenerateToken();

		return redirect(route('home'));
	}
}
