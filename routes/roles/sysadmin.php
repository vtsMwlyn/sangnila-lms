<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/sysadmin') // 80%
	->name('sysadmin.')
	->middleware(['auth', /*'role:SysAdmin',*/ 'role:Admin', 'verified'])
	->group(function () {
		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		// Admin Account
		Route::prefix('/account') // 90%
			->name('account.')
			->group(function () {
				// Admin account controller
				Route::get('/', [AdminAccountController::class, 'index'])->name('index'); // DONE
				Route::get('/{user_id}', [AdminAccountController::class, 'show_acc'])->name('show')->whereNumber('user_id'); // HALT

				// Create special form to make administrator accounts
				Route::get('/create', [RegisteredUserController::class, 'sys_create'])->name('create'); // DONE
				Route::post('/', [RegisteredUserController::class, 'sys_store'])->name('store'); // DONE

				Route::get("/{user_id}/edit", [AdminAccountController::class, "edit_acc"])->name("acc_edit");
				Route::post("/{user_id}/edit", [AdminAccountController::class, "update_acc"])->name("acc_edit.store");
				Route::post("/{user_id}/delete", [AdminAccountController::class, "destroy"])->name("acc_delete");
				Route::post("/{user_id}/disable", [AdminAccountController::class, "disable_acc"])->name("acc_disable");
			});

		// Create Course
		Route::prefix('/course') // 90%
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
