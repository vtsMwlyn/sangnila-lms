<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
			dd('SysAdmin');
			break;
		case 'Admin':
			// Logic for Admin
			dd('Admin');
			break;
		case 'Teacher':
			// Logic for Teacher
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
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php'; // to be deleted

require __DIR__ . '/roles/sysadmin.php';
require __DIR__ . '/roles/admin.php';
require __DIR__ . '/roles/teacher.php';
require __DIR__ . '/roles/student.php';
require __DIR__ . '/roles/guest.php';
