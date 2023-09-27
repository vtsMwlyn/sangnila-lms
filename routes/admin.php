<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')
	->name('admin.')
	->group(function () {
		// Teacher Account
		Route::prefix('/teacher')
			->name('teacher.')
			->group(function () {
				// Teacher account controller
				Route::get('/', [TeacherAccountController::class, 'index'])->name('index');
				Route::get('/{teacher_id}', [TeacherAccountController::class, 'show'])->name('show')
					->whereNumber('teacher_id');

				// Create special form to make administrator accounts
				Route::get('/create')->name('create');
				Route::post('/')->name('store');
			});

		Route::prefix('/course')
			->name('course.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'index'])->name('index');
				Route::get('/{course_id}', [CourseController::class, 'show'])->name('show')->whereNumber('course_id');

				//Edit Course
				Route::get('/{course_id}/edit', [CourseController::class,'edit'])->name('edit')->whereNumber('course_id');
				Route::patch('/course/{course_id}', [CourseController::class, 'update'])->name('update')->whereNumber('course_id');

				// ==========================================================================

				// Assign Teacher (CourseTeacherController)
				Route::get('/assign/{course_id}')->name('assign')->whereNumber('course_id');
				Route::post('/assign/{course_id}')->name('store')->whereNumber('course_id');

				// Unassign Teacher (CourseTeacherController)
				// idea-1: a direct link to a specific page, where course_id, and a list of teacher with teacher_id as the input
				// idea-2: form with course_teacher id
				Route::get('/unassign/{course_id}')->name('unassign')->whereNumber('course_id'); // Deletion confirmation
				Route::delete('/unassign/{course_id}')->name('destroy.unassign')->whereNumber('course_id');
			});

		Route::prefix('/schedule')
			->name('schedule.')
			->group(function () {
				// Schedule Controller (course_schedule)
				Route::get('/')->name('index');
				Route::get('/{schedule_id}')->name('show')->whereNumber('schedule_id');

				// Create Schedule (course_schedule)
				Route::get('/create')->name('create');
				Route::post('/')->name('store');

				// Edit Schedule (course_schedule);
				Route::get('/{schedule_id}/edit')->name('edit')->whereNumber('schedule_id');
				Route::patch('/{schedule_id}')->name('update')->whereNumber('schedule_id');

				// Assign schedules (student_schedule)
				// Student Controller
				Route::get('/{schedule_id}/assign')->name('assign')->whereNumber('schedule_id'); // Show student where course->students
				Route::post('/{schedule_id}')->name('store')->whereNumber('schedule_id'); // Accepts student_id as the input
			});

		Route::prefix('/student')
			->name('student.')
			->group(function () {
				// Student Controller
				Route::get('/')->name('index');
				Route::get('/{student_id}')->name('show')->whereNumber('student_id');

				// Student Controller
				Route::get('/{student_id}/{course_id}')->name('show.schedule')->whereNumber('student_id')->whereNumber('course_id');
				Route::get('/{student_id}/{course_id}/edit')->name('edit.schedule')->whereNumber('student_id')->whereNumber('course_id');
				Route::patch('/{student_id}/{course_id}')->name('update.schedule')->whereNumber('student_id')->whereNumber('course_id');
			});
	});
