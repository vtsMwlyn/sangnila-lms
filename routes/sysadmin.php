<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/sysadmin')
	->name('sysadmin.')
	->group(function () {
		// Admin Account
		Route::prefix('/account')
			->name('account.')
			->group(function() {
				// Admin account controller
				Route::get('/')->name('index');
				Route::get('/{user_id}')->name('show')->whereNumber('user_id');

				Route::get('/create')->name('create');
				Route::post('/')->name('store');

			});

		// Create Course
		Route::prefix('/course')
			->name('course.')
			->group(function() {
				// Course Controller
				Route::get('/')->name('index');
				Route::get('/{course_id}')->name('show')->whereNumber('course_id');

				Route::get('/create')->name('create');
				Route::post('/')->name('store');

				Route::get('/{course_id}/archive')->name('archive')->whereNumber('course_id'); // Confirmation
				Route::patch('/{course_id}')->name('update.archive')->whereNumber('course_id'); // Change visibility to private
			});
});
