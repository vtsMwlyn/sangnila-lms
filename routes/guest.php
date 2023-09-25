<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/guest')
	->name('guest.')
	->group(function() {
		Route::get('/')->name('index');
		Route::get('/{course_id}')->name('show')->whereNumber('course_id');
	});
