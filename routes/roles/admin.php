<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAccountController;
use App\Models\CourseTeacher;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')
	->name('admin.')
	->middleware(['auth', 'role:Admin', 'verified', "acc_not_disabled"])
	->group(function () {

		// Landing page
		Route::get('/', function () {
			return redirect(route('dashboard'));
		});

		// Manage courses
		Route::prefix('/course')
			->name('course.')
			->group(function () {

				// Show all courses (including private/not public courses)
				Route::get('/', [CourseController::class, 'admin_index'])->name('index');

				// Course details
				Route::get('/{course_id}', [CourseController::class, 'admin_show'])->name('show')->whereNumber('course_id');

				// Edit course data
				Route::get('/{course_id}/edit', [CourseController::class, 'admin_edit'])->name('edit')->whereNumber('course_id');
				Route::patch('/{course_id}', [CourseController::class, 'admin_update'])->name('update')->whereNumber('course_id');

				// Delete course
				Route::get("/{course_id}/delete", [CourseController::class, "admin_delete"])->name("delete");
				Route::delete('/{course_id}/delete', [CourseController::class, 'admin_destroy'])->name('destroy')->whereNumber('course_id');

			}
		);

		// Manage teachers
		Route::prefix('/teacher')
			->name('teacher.')
			->group(function () {

				// Active teachers list
				Route::get('/', [TeacherAccountController::class, 'index'])->name('index');

				// Teacher details
				Route::get('/{teacher_id}', [TeacherAccountController::class, 'show'])->name('show')
					->whereNumber('teacher_id');

				// Edit teacher data
				Route::get('/{teacher_id}/edit', [TeacherAccountController::class, 'admin_edit'])->name('edit')->whereNumber('teacher_id');
				Route::patch('/{teacher_id}', [TeacherAccountController::class, 'admin_update'])->name('update')->whereNumber('teacher_id');

				// Assign/unassign teacher to/from a course
				Route::get("/{teacher_id}/assign", [CourseTeacherController::class, "show"])->name("assign");
				Route::post("/{teacher_id}/assign", [CourseTeacherController::class, "assign"])->name("assign.store");

				Route::get('{teacher_id}/unassign/{course_id}', [CourseTeacherController::class, 'delete'])->name('unassign.delete');
				Route::delete("/{teacher_id}/unassign/{course_id}", [CourseTeacherController::class, "unassign"])->name("unassign.destroy");

			}
		);

		// Manage Students
		Route::prefix('/student')
			->name('student.')
			->group(function () {

				// List of active students
				Route::get('/', [StudentController::class, 'admin_index'])->name('index');
				Route::get('/{student_id}', [StudentController::class, 'admin_show'])->name('show')->whereNumber('student_id');

				// Edit student data
				Route::get('/{student_id}/edit', [StudentController::class, 'admin_edit'])->name('edit')->whereNumber('student_id');
				Route::patch('/{student_id}', [StudentController::class, 'admin_update'])->name('update')->whereNumber('student_id');

				// Assign/unassign students to/from a course
				Route::get('/{student_id}/assign', [CourseStudentController::class, 'create'])->name('assign.create');
				Route::post('/{student_id}/assign', [CourseStudentController::class, 'store'])->name('assign.store');

				Route::get('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'delete'])->name('unassign.delete');
				Route::delete('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'destroy'])->name('unassign.destroy');

				// Assignment and attendance details
				Route::get("{student_id}/{course_id}/assignments", [AssignmentController::class, "admin_show"])->name("asg-details");
				Route::get("{student_id}/{course_id}/attendance", [AttendanceController::class, "admin_show"])->name("atd-details");

			}
		);
	});
