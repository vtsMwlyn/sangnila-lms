<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/sysadmin')
	->name('sysadmin.')
	->middleware(['auth', /*'role:SysAdmin',*/ 'role:Admin', 'verified'])
	->group(function () {

		// Landing page
		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		// Manage Accounts (Soon will be integrated to route admin.php)
		Route::prefix('/account')
			->name('account.')
			->group(function () {

				// List of available accounts (categorized to enabled/disabled)
				Route::get('/', [AdminAccountController::class, 'index'])->name('index');

				// Account details
				Route::get('/{user_id}', [AdminAccountController::class, 'show_acc'])->name('show')->whereNumber('user_id');

				// Create new account
				Route::get('/create', [RegisteredUserController::class, 'sys_create'])->name('create');
				Route::post('/', [RegisteredUserController::class, 'sys_store'])->name('store');

				// Edit account data
				Route::get("/{user_id}/edit", [AdminAccountController::class, "edit_acc"])->name("acc_edit");
				Route::patch("/{user_id}/edit", [AdminAccountController::class, "update_acc"])->name("acc_edit.store");

				// Delete account data
				Route::get("/{user_id}/delete", [AdminAccountController::class, "delete"])->name("acc_delete");
				Route::delete("/{user_id}/delete", [AdminAccountController::class, "destroy"])->name("destroy");

				// Enable/disable account
				Route::post("/{user_id}/disable", [AdminAccountController::class, "disable_acc"])->name("acc_disable");
				Route::post("/{user_id}/enable", [AdminAccountController::class, "enable_acc"])->name("acc_enable");

			}
		);

		// Manage Courses (Soon will be integrated to route admin.php)
		Route::prefix('/course')
			->name('course.')
			->group(function () {

				// Create new course
				Route::get('/create', [CourseController::class, 'sys_create'])->name('create');
				Route::post('/', [CourseController::class, 'sys_store'])->name('store');

			}
		);

	}
);
