<?php

use App\Http\Controllers\AssignmentController;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;

Route::prefix('/student')
	->name('student.')
	->middleware(['auth', 'role:Student', 'verified', "acc_not_disabled"])
	->group(function() {

		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		Route::prefix('/mycourse') // DONE
			->name('mycourse.')
			->group(function(){
				Route::get('/', [CourseController::class, 'student_index'])->name('index'); // DONE
				Route::get('/{course_id}', [CourseController::class, 'student_show'])->name('show')->whereNumber('course_id'); // DONE
			});

		Route::prefix('/attendance')
			->name('attendance.')
			->group(function() {
				Route::get('/', [AttendanceController::class, "student_index"])->name('index'); // Select course before continue to see attendance data in the course
				Route::get('/{course_id}', [AttendanceController::class, "student_show"])->name('show'); // Select course before continue to see attendance data in the course
			});

		Route::prefix('/assignment')
			->name('assignment.')
			->group(function() {
				Route::get('/', [AssignmentController::class, "student_index"])->name('index'); // Select course before continue to see assignment list in the course
				Route::get("/{course_id}", [AssignmentController::class, "student_show"])->name("show"); // Show list of assignments assigned in the course
			});

		/* Route::prefix('/schedule') // DONE
		->name('schedule.')
		->group(function() {
			Route::get('/', [ScheduleController::class, 'student_index'])->name('index'); // Show student schedules table
		}); */


	});
