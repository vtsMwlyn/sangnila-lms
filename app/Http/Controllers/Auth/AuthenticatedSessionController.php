<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AnnouncementUser;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller {
	/**
	 * Display the login view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create() {
		return view('auth.login');
	}

	/**
	 * Handle an incoming authentication request.
	 *
	 * @param  \App\Http\Requests\Auth\LoginRequest  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(LoginRequest $request) {
		$request->authenticate();

		$request->session()->regenerate();

		session(['show_announcement' => true]);

		// // To make announcement re-appear after 1 hours since last opened
		// $announcementKey = 'announcement_displayed_at';
		// $redisplayInterval = now()->subHours(1); // 1 hours ago

		// // Check if the announcement should be redisplayed
		// if (!Cache::has($announcementKey) || Cache::get($announcementKey) < $redisplayInterval) {
		// 	// Set the cache timestamp to now
		// 	Cache::put($announcementKey, now());

		// 	// Set session flag to show the announcement
		// 	session(['show_announcement' => true]);
		// } else {
		// 	session(['show_announcement' => false]);
		// }

		return redirect()->intended(route('dashboard'));
	}

	// public function store(Request $request) {
	// 	$validatedData = $request->validate([
	// 		"email" => "required|email:dns",
	// 		"password" => "required|min:8"
	// 	]);

	// 	if(Auth::attempt($validatedData)){
	// 		$request->session()->regenerate();
	// 		return redirect()->intended(route('dashboard'));
	// 	}

	// 	return back()->with("failLogin", "Login failed!");
	// }

	/**
	 * Destroy an authenticated session.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Request $request) {
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
