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

	}
);
