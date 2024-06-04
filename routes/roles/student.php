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

		// Landing page
		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		// My courses
		Route::prefix('/mycourse')
			->name('mycourse.')
			->group(function(){

				// List of enrolled courses
				Route::get('/', [CourseController::class, 'student_index'])->name('index');

				// Course details and available topics and materials
				Route::get('/{course_id}', [CourseController::class, 'student_show'])->name('show')->whereNumber('course_id');

			}
		);

		// Assignment
		Route::prefix('/assignment')
			->name('assignment.')
			->group(function() {

				// Pick an intended course to show assignment
				Route::get('/', [AssignmentController::class, "student_index"])->name('index');

				// List of assigned assignments in the selected course
				Route::get("/{course_id}", [AssignmentController::class, "student_show"])->name("show");

				// Upload assignment
				Route::get("/{course_id}/{assignment_id}/submit", [AssignmentController::class, "student_submit"])->name("submit");
				Route::post("/{course_id}/{assignment_id}/submit", [AssignmentController::class, "student_store"])->name("store");

				// Check submission history
				Route::get("/{course_id}/{student__id}/{assignment_id}/detail", [AssignmentController::class, "student_submission_detail"])->name("detail");

			}
		);

		// Attendance
		Route::prefix('/attendance')
			->name('attendance.')
			->group(function() {

				// Pick an intended course to show attendance data
				Route::get('/', [AttendanceController::class, "student_index"])->name('index');

				// List of attendance data in the selected course
				Route::get('/{course_id}', [AttendanceController::class, "student_show"])->name('show');

			}
		);

	}
);
