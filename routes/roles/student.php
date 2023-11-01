<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('/student')
	->name('student.')
	->middleware(['auth', 'role:Student'])
	->group(function() {

		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		Route::prefix('/attendance')
			->name('attendance.')
			->group(function() {
				Route::get('/')->name('index'); // Show attendance table
				Route::patch('/{attendance_id}')->name('update'); // Update the attendance status (Select input), with input:reason
			});

			/* Route::prefix('/schedule') // DONE
			->name('schedule.')
			->group(function() {
				Route::get('/', [ScheduleController::class, 'student_index'])->name('index'); // Show student schedules table
			}); */

			Route::prefix('/mycourse') // DONE
				->name('mycourse.')
				->group(function(){
					Route::get('/', [CourseController::class, 'student_index'])->name('index'); // DONE
					Route::get('/{course_id}', [CourseController::class, 'student_show'])->name('show')->whereNumber('course_id'); // DONE
				});
	});
