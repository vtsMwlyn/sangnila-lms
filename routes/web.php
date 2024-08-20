<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('/', function () {
	if (Auth::check()) {
		// User is logged in, so redirect to a specific route
		return redirect()->route('dashboard');
	}
	return view('roles.guest.home');
})->name('home');

Route::get('/dashboard', function () {
	$roleName = Auth::user()->role->role_name;
	switch ($roleName) {
		case 'SysAdmin':
			// Logic for SysAdmin
			return redirect(route('sysadmin.account.index'));
			dd('SysAdmin');
			break;
		case 'Admin':
			// Logic for Admin
			return redirect(route('admin.course.index'));
			dd('Admin');
			break;
		case 'Teacher':
			// Logic for Teacher
			return redirect(route('teacher.mycourse.index'));
			dd('Teacher');
			break;
		case 'Student':
			// Logic for Student
			return redirect(route('student.mycourse.index'));
			dd('Student');
			break;
		default:
			// Default behavior (e.g., for unknown roles)
			return view('dashboard');
	}
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix("/profile")->name("profile.")->middleware(["auth", "verified"])->group(function(){
	Route::get("/", [UserAccountController::class, "show"])->name("show");
	Route::post("/", [UserAccountController::class, "update"])->name("update");
});

Route::prefix("/notification")->name("notification.")->middleware(["auth", "verified"])->group(function(){
	Route::post("/{notification_id}", [NotificationController::class, "mark_as_read"])->name("mark-read")->whereNumber("notification_id");
	Route::post("/mark-read-all", [NotificationController::class, "mark_all_as_read"])->name("mark-all-read");
});


require __DIR__ . '/auth.php'; // to be deleted

require __DIR__ . '/roles/admin.php';
require __DIR__ . '/roles/teacher.php';
require __DIR__ . '/roles/student.php';
require __DIR__ . '/roles/guest.php';
