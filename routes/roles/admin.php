<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAccountController;
use App\Models\CourseTeacher;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin') // ON PROGRESS
	->name('admin.')
	->middleware(['auth', 'role:Admin', 'verified', "acc_not_disabled"])
	->group(function () {
		Route::get('/', function () {
			return redirect(route('dashboard'));
		});

		// Teacher Account
		Route::prefix('/teacher') // ON HALT 50%
			->name('teacher.')
			->group(function () {
				// Teacher account controller
				Route::get('/', [TeacherAccountController::class, 'index'])->name('index'); // DONE
				Route::get('/{teacher_id}', [TeacherAccountController::class, 'show'])->name('show') // DONE
					->whereNumber('teacher_id');

				// Edit teacher data
				Route::get('/{teacher_id}/edit', [TeacherAccountController::class, 'admin_edit'])->name('edit')->whereNumber('teacher_id');
				Route::patch('/{teacher_id}', [TeacherAccountController::class, 'admin_update'])->name('update')->whereNumber('teacher_id');

				// Assign and unassign teacher to a course
				Route::get("/{teacher_id}/assign", [CourseTeacherController::class, "show"])->name("assign");
				Route::post("/{teacher_id}/assign", [CourseTeacherController::class, "assign"])->name("assign.store");
				Route::post("/{teacher_id}/unassign", [CourseTeacherController::class, "unassign"])->name("unassign");

				# TODO // ON HALT
				// Create special form to make teacher accounts
				// Route::get('/create')->name('create');
				// Route::post('/')->name('store');
			}
		);

		// All Course List
		Route::prefix('/course') // ON PROGRESS 90%
			->name('course.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'admin_index'])->name('index'); // DONE
				Route::get('/{course_id}', [CourseController::class, 'admin_show'])->name('show')->whereNumber('course_id'); // DONE

				//Edit Course
				Route::get('/{course_id}/edit', [CourseController::class, 'admin_edit'])->name('edit')->whereNumber('course_id'); // DONE
				Route::patch('/{course_id}', [CourseController::class, 'admin_update'])->name('update')->whereNumber('course_id'); // DONE

				// Delete Course
				Route::delete('/{course_id}', [CourseController::class, 'admin_destroy'])->name('delete')->whereNumber('course_id');

				// ==========================================================================

				// Assign Teacher (CourseTeacherController)
				Route::get('{course_id}/assign/', [CourseTeacherController::class, 'create'])->name('assign')->whereNumber('course_id'); // DONE
				Route::post('{course_id}/assign/', [CourseTeacherController::class, 'store'])->name('assign_store')->whereNumber('course_id'); // DONE


				# TODO
				// Unassign Teacher (CourseTeacherController)
				// idea-1: a direct link to a specific page, where course_id, and a list of teacher with teacher_id as the input
				// idea-2: form with course_teacher id
				Route::get('/{course_id}/unassign/{teacher_id}', [CourseController::class, 'unassign'])->name('unassign')->whereNumber('course_id'); // Deletion confirmation
				Route::delete('/{course_id}/unassign{teacher_id}', [CourseTeacherController::class, 'unassign_destroy'])->name('destroy.unassign')->whereNumber('course_id');
			}
		);

		//Schedules
		/* Route::prefix('/schedule') // ON PROGRESS
			->name('schedule.')
			->group(function () {
				// Schedule Controller (course_schedule)
				Route::get('/', [ScheduleController::class, 'admin_index'])->name('index'); // DONE
				Route::get('/{schedule_id}', [ScheduleController::class, 'admin_show'])->name('show')->whereNumber('schedule_id');

				// Create Schedule (course_schedule)
				Route::get('/create', [ScheduleController::class, 'admin_create'])->name('create'); // DONE
				Route::post('/', [ScheduleController::class, 'admin_store'])->name('store'); // DONE


				// Edit Schedule (course_schedule);
				Route::get('/{schedule_id}/edit', [ScheduleController::class, 'admin_edit'])->name('edit')->whereNumber('schedule_id'); // DONE
				Route::patch('/{schedule_id}', [ScheduleController::class, 'admin_update'])->name('update')->whereNumber('schedule_id'); // DONE

				// Assign schedules (student_schedule)
				// Student Controller
				Route::get('/{schedule_id}/assign', [ScheduleController::class, 'assign'])->name('assign')->whereNumber('schedule_id'); // DONE
				Route::post('/{schedule_id}', [ScheduleController::class, 'assign_store'])->name('assign_store')->whereNumber('schedule_id'); // DONE
			}); */

		// Student List
		Route::prefix('/student') // DONE
			->name('student.')
			->group(function () {
				// Student Controller
				Route::get('/', [StudentController::class, 'admin_index'])->name('index'); // DONE
				Route::get('/{student_id}', [StudentController::class, 'admin_show'])->name('show')->whereNumber('student_id'); //DONE

				// Edit student data
				Route::get('/{student_id}/edit', [StudentController::class, 'admin_edit'])->name('edit')->whereNumber('student_id');
				Route::patch('/{student_id}', [StudentController::class, 'admin_update'])->name('update')->whereNumber('student_id');

				// Assign
				Route::get('/{student_id}/assign', [CourseStudentController::class, 'create'])->name('assign.create'); // DONE
				Route::post('/{student_id}/assign', [CourseStudentController::class, 'store'])->name('assign.store'); // DONE

				// Unassign
				Route::get('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'delete'])->name('unassign.delete'); // DONE
				Route::delete('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'destroy'])->name('unassign.destroy'); //DONE
			}
		);
	});
