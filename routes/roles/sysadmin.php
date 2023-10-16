<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/sysadmin')
	->name('sysadmin.')
	->middleware(['auth', 'role:SysAdmin'])
	->group(function () {
		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		// Admin Account
		Route::prefix('/account')
			->name('account.')
			->group(function () {
				// Admin account controller
				Route::get('/', [AdminAccountController::class, 'index'])->name('index'); // DONE
				Route::get('/{user_id}', [AdminAccountController::class, 'show'])->name('show')->whereNumber('user_id'); // HALT

				// Create special form to make administrator accounts
				Route::get('/create', [RegisteredUserController::class, 'sys_create'])->name('create'); // DONE
				Route::post('/', [RegisteredUserController::class, 'sys_store'])->name('store'); // DONE
			});

		// Create Course
		Route::prefix('/course')
			->name('course.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'sys_index'])->name('index'); // DONE
				Route::get('/{course_id}', [CourseController::class, 'sys_show'])->name('show')->whereNumber('course_id'); // DONE

				Route::get('/create', [CourseController::class, 'sys_create'])->name('create'); // DONE
				Route::post('/', [CourseController::class, 'sys_store'])->name('store'); // DONE

				Route::get('/{course_id}/archive', [CourseController::class, 'sys_archive_confirm'])->name('confirm.archive')->whereNumber('course_id'); // Confirmation // HALT (gatau dipakeato ga)
				Route::patch('/{course_id}', [CourseController::class, 'sys_archive_update'])->name('update.archive')->whereNumber('course_id'); // Change visibility to private // DONE
			});

	});
