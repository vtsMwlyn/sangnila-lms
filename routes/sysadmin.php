<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/sysadmin')
	->name('sysadmin.')
	->group(function () {
		// Admin Account
		Route::prefix('/account')
			->name('account.')
			->group(function () {
				// Admin account controller
				Route::get('/', [AdminAccountController::class, 'index'])->name('index');
				Route::get('/{user_id}', [AdminAccountController::class, 'show'])->name('show')->whereNumber('user_id');

				// Create special form to make administrator accounts
				Route::get('/create',)->name('create');
				Route::post('/')->name('store');
			});

		// Create Course
		Route::prefix('/course')
			->name('course.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'sys_index'])->name('index');
				Route::get('/{course_id}', [CourseController::class, 'sys_show'])->name('show')->whereNumber('course_id');

				Route::get('/create', [CourseController::class, 'sys_create'])->name('create');
				Route::post('/', [CourseController::class, 'sys_store'])->name('store');

				Route::get('/{course_id}/archive', [CourseController::class, 'sys_archive_confirm'])->name('conrifm.archive')->whereNumber('course_id'); // Confirmation
				Route::patch('/{course_id}', [CourseController::class, 'sys_archive_update'])->name('update.archive')->whereNumber('course_id'); // Change visibility to private
			});
	});
