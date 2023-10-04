<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin') // ON PROGRESS
	->name('admin.')
	->group(function () {
		// Teacher Account
		Route::prefix('/teacher')
			->name('teacher.')
			->group(function () {
				// Teacher account controller
				Route::get('/', [TeacherAccountController::class, 'index'])->name('index'); // DONE
				Route::get('/{teacher_id}', [TeacherAccountController::class, 'show'])->name('show') // DONE
					->whereNumber('teacher_id');


				# TODO
				// Create special form to make teacher accounts
				Route::get('/create')->name('create');
				Route::post('/')->name('store');
			});

		Route::prefix('/course')
			->name('course.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'admin_index'])->name('index'); // DONE
				Route::get('/{course_id}', [CourseController::class, 'admin_show'])->name('show')->whereNumber('course_id'); // DONE

				//Edit Course
				Route::get('/{course_id}/edit', [CourseController::class,'admin_edit'])->name('edit')->whereNumber('course_id'); // DONE
				Route::patch('/{course_id}', [CourseController::class, 'admin_update'])->name('update')->whereNumber('course_id'); // DONE

				// ==========================================================================

				// Assign Teacher (CourseTeacherController)
				Route::get('{course_id}/assign/', [CourseTeacherController::class, 'create'])->name('assign')->whereNumber('course_id'); // DONE
				Route::post('{course_id}/assign/', [CourseTeacherController::class, 'store'])->name('assign_store')->whereNumber('course_id'); // DONE


				# TODO
				// Unassign Teacher (CourseTeacherController)
				// idea-1: a direct link to a specific page, where course_id, and a list of teacher with teacher_id as the input
				// idea-2: form with course_teacher id
				Route::get('/{course_id}/unassign', [CourseController::class, 'unassign'])->name('unassign')->whereNumber('course_id'); // Deletion confirmation
				Route::delete('/{course_id}/unassign', [CourseTeacherController::class, 'unassign_destroy'])->name('destroy.unassign')->whereNumber('course_id');
			});

		Route::prefix('/schedule') // SOON
			->name('schedule.')
			->group(function () {
				// Schedule Controller (course_schedule)
				Route::get('/', [ScheduleController::class, 'index'])->name('index');
				Route::get('/{schedule_id}', [ScheduleController::class, 'show'])->name('show')->whereNumber('schedule_id');

				// Create Schedule (course_schedule)
				Route::get('/create', [ScheduleController::class, 'create'])->name('create');
				Route::post('/', [ScheduleController::class, 'store'])->name('store');

				// Edit Schedule (course_schedule);
				Route::get('/{schedule_id}/edit', [ScheduleController::class, 'edit'])->name('edit')->whereNumber('schedule_id');
				Route::patch('/{schedule_id}', [ScheduleController::class, 'update'])->name('update')->whereNumber('schedule_id');

				// Assign schedules (student_schedule)
				// Student Controller
				Route::get('/{schedule_id}/assign')->name('assign')->whereNumber('schedule_id'); // Show student where course->students
				Route::post('/{schedule_id}')->name('store')->whereNumber('schedule_id'); // Accepts student_id as the input
			});

		Route::prefix('/student')
			->name('student.')
			->group(function () {
				// Student Controller
				Route::get('/', [StudentController::class, 'index'])->name('index');
				Route::get('/{student_id}', [StudentController::class, 'show'])->name('show')->whereNumber('student_id');

				// student_schedule Controller
				Route::get('/{student_id}/{course_id}', [StudentController::class, 'student_show'])->name('show.schedule')->whereNumber('student_id')->whereNumber('course_id');
				Route::get('/{student_id}/{course_id}/edit', [StudentController::class, 'student_edit'])->name('edit.schedule')->whereNumber('student_id')->whereNumber('course_id');
				Route::patch('/{student_id}/{course_id}', [StudentController::class, 'student_update'])->name('update.schedule')->whereNumber('student_id')->whereNumber('course_id');
			});
	});
