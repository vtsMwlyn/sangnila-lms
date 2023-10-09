<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')
	->name('teacher.')
	->group(function() {
		Route::prefix('/mycourse')
		->name('mycourse.')
		->group(function() {
			// Course Controller
			Route::get('/', [CourseController::class, 'teacher_index'])->name('index'); // DONE
			Route::get('/{course_id}', [CourseController::class, 'teacher_show'])->name('show')->whereNumber('course_id'); // DONE
		});

		Route::prefix('/material') // PROGRESS
			->name('material.')
			->group(function() {
				// Material Controller
				Route::get('/upload/{course_id}', [MaterialController::class, 'teacher_create'])->name('upload')->whereNumber('course_id'); // DONE
				Route::post('/upload/{course_id}', [MaterialController::class, 'teacher_store'])->name('store')->whereNumber('course_id'); // DONE

				Route::get('/{material_id}/edit', [MaterialController::class, 'teacher_edit'])->name('edit')->whereNumber('material_id'); // DONE
				Route::patch('/{material_id}', [MaterialController::class, 'teacher_update'])->name('update')->whereNumber('material_id'); // DONE

				Route::get('/{material_id}/delete')->name('remove')->whereNumber('material_id'); // SOON
				Route::delete('/{material_id}')->name('destroy')->whereNumber('material_id'); // SOON
			});

		Route::prefix('/student') // Progress
			->name('student.')
			->group(function() {
				// Student Controller
				Route::get('/', [StudentController::class, 'teacher_index'])->name('index'); // DONE
				Route::get('/{student_id}', [StudentController::class, 'teacher_show'])->name('show')->whereNumber('student_id'); // Done

				// Progress Controller
				// TODO
				Route::get('/{student_id}/{course_id}')->name('show.progress')->whereNumber('student_id')->whereNumber('student_id'); // Show progression table
				Route::patch('/progress/{progress_id}')->name('update.progress')->whereNumber('progress_id');

			});

		Route::prefix('/schedule')
			->name('schedule.')
			->group(function() {
				// Schedule Controller
				Route::get('/')->name('index'); // Show all schedule for the teacher
				Route::get('/{schedule}')->name('show.student')->whereNumber('schedule_id');

				Route::prefix('/verify')
					->name('vefiry.')
					->group(function() {
						// Attendance Controller (student_attendance)
						Route::get('/')->name('index'); // Show a list of student teached by this teacher
						Route::get('/{student_id}')->name('show'); // Show student attendance table

						Route::get('/{student_id}/validate')->name('validate')->whereNumber('student_id');
						Route::patch('/{student_id}')->name('update')->whereNumber('student_id');
					});

			});
	});
