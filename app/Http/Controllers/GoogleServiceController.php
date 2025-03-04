<?php

namespace App\Http\Controllers;

use Google_Client;
use App\Models\User;

use Google_Service_Tasks;
use Google_Service_Oauth2;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleServiceController extends Controller
{
	private $google_service_scope = [
		Google_Service_Oauth2::USERINFO_PROFILE,
		Google_Service_Oauth2::USERINFO_EMAIL,
		Google_Service_Calendar::CALENDAR_READONLY,
		Google_Service_Tasks::TASKS_READONLY,
	];

    // Redirect the user to Google's OAuth authorization page
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CALENDAR_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CALENDAR_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
        $client->addScope($this->google_service_scope);
		$client->setAccessType('offline'); // Request offline access to get the refresh token
    	$client->setApprovalPrompt('force'); // Ensures the user always gets a refresh token

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    // Handle the OAuth callback
    public function handleGoogleCallback(Request $request)
	{
		$client = new Google_Client();
		$client->setClientId(env('GOOGLE_CALENDAR_CLIENT_ID'));
		$client->setClientSecret(env('GOOGLE_CALENDAR_CLIENT_SECRET'));
		$client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
		$client->addScope($this->google_service_scope);

		// Exchange the authorization code for access and refresh tokens
		$token = $client->fetchAccessTokenWithAuthCode($request->code);

		if (isset($token['error'])) {
			return redirect()->route('dashboard')->with('error', 'Google login failed');
		}

		// Store the access token and refresh token in the session or database
		session(['google_access_token' => $token['access_token']]);

		// Logged in user information
		$oauthService = new Google_Service_Oauth2($client);
		$userInfo = $oauthService->userinfo->get();

		// User Registration
		$appUser = Auth::user();

		// Store refresh token if available (only returned on first authorization)
		if (isset($token['refresh_token'])) {
			$appUser->update(['google_refresh_token' => $token['refresh_token']]);
		} else {
			// If refresh token is missing, retrieve it from the database
			$existingToken = $appUser->google_refresh_token;
			if ($existingToken) {
				session(['google_refresh_token' => $existingToken]);
			}
		}

		session(['google_user_info' => [
			'name' => $userInfo->name,
			'email' => $userInfo->email,
			'picture' => $userInfo->picture,
		]]);
		session()->save();

		return redirect()->route('dashboard');
	}

	public function refreshGoogleAccessToken(Google_Client $client)
	{
		// Set up the Google client
		$client->addScope($this->google_service_scope);

		// Retrieve stored refresh token
		$refreshToken = session('google_refresh_token') ?? Auth::user()->google_refresh_token;

		if (!$refreshToken) {
			return response()->json(['error' => 'No refresh token available. Please re-authenticate.'], 401);
		}

		// Check if the access token is expired
		if ($client->isAccessTokenExpired()) {
			// Refresh the access token
			$newAccessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

			if (isset($newAccessToken['error'])) {
				return response()->json(['error' => 'Failed to refresh token', 'details' => $newAccessToken], 400);
			}

			// Save the new access token in session
			session(['google_access_token' => $newAccessToken['access_token']]);

			// If a new refresh token is provided, update it in the database
			if (isset($newAccessToken['refresh_token'])) {
				User::findOrFail(Auth::user()->id)->update(['google_refresh_token' => $newAccessToken['refresh_token']]);
				session(['google_refresh_token' => $newAccessToken['refresh_token']]);
			}

			session()->save();
		}
	}

	public function logout()
    {
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

        // Redirect to a homepage or login page
        return redirect()->back();
    }
}
