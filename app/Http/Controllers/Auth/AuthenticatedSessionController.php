<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AnnouncementUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller {
	public function create() {
		return view('auth.login');
	}

	public function store(LoginRequest $request) {
		$request->authenticate();

		$request->session()->regenerate();

		$user = Auth::user();
		User::findOrFail($user->id)->update(["last_login" => Carbon::now()]);

		return redirect()->intended(route('dashboard'));
	}

	public function destroy(Request $request) {
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
