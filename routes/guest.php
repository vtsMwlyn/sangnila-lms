<?php

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guest')
	->name('guest.')
	->group(function() {
		Route::get('/', [GuestController::class, 'index'])->name('index');
		Route::get('/{course_id}', [GuestController::class, 'show'])->name('show')->whereNumber('course_id');
	});
