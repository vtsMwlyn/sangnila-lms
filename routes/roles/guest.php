<?php

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guest')
	->name('guest.')
	->group(function() {

		// Landing page (by default also showing list of available courses in Sangnila LMS)
		Route::get('/', [GuestController::class, 'index'])->name('index');

		// Course details and list of trial (guest-accessible/available or first) topic/activity
		Route::get('/{course_id}', [GuestController::class, 'show'])->name('show')->whereNumber('course_id');

		// Course details and list of trial (guest-accessible/available or first) topic/activity
		Route::get('/{course_id}/{activity_id}', [GuestController::class, 'preview'])->name('preview')->whereNumber('course_id')->whereNumber('activity_id');

		// Privacy policy
		Route::get('/privacy-policy', function(){
			return view('roles.guest.privacy-policy');
		})->name('privacy-policy');

	}
);
