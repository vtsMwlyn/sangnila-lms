<?php

use App\Models\CourseTeacher;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\TeacherAccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CurriculumController;

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

				// Create new course
				Route::get('/create', [CourseController::class, 'admin_create'])->name('create');
				Route::post('/', [CourseController::class, 'admin_store'])->name('store');

				// Course details
				Route::get('/{course_id}', [CourseController::class, 'admin_show'])->name('show')->whereNumber('course_id');

				// Batch assign student
				Route::get("/{course_id}/batch-assign", [CourseStudentController::class, "batch_assign"])->name("batch-assign");
				Route::post("/{course_id}/batch-assign", [CourseStudentController::class, "batch_assign_store"])->name("batch-assign.store");

				// Batch import old student data
				Route::get("/{course_id}/import-data", [CourseStudentController::class, "import_student_data"])->name("import-student-data");
				Route::post("/{course_id}/import-data", [CourseStudentController::class, "import_student_data_store"])->name("import-student-data.store");

				// Edit course data
				Route::get('/{course_id}/edit', [CourseController::class, 'admin_edit'])->name('edit')->whereNumber('course_id');
				Route::patch('/{course_id}', [CourseController::class, 'admin_update'])->name('update')->whereNumber('course_id');

				// Delete course
				Route::get("/{course_id}/delete", [CourseController::class, "admin_delete"])->name("delete");
				Route::delete('/{course_id}/delete', [CourseController::class, 'admin_destroy'])->name('destroy')->whereNumber('course_id');

				// Manage curriculum
					Route::prefix('/{course_id}/curriculum')
					->name('curriculum.')
					->group(function () {
						Route::get("/create", [CurriculumController::class, "admin_create_topic"])->name("topic.create");
						Route::post("/create", [CurriculumController::class, "admin_store_topic"])->name("topic.store");

						Route::get("/{curriculum_topic_id}/edit", [CurriculumController::class, "admin_edit_topic"])->name("topic.edit");
						Route::post("/{curriculum_topic_id}/edit", [CurriculumController::class, "admin_update_topic"])->name("topic.update");

						Route::get("/{curriculum_topic_id}/delete", [CurriculumController::class, "admin_delete_topic"])->name("topic.delete");
						Route::post("/{curriculum_topic_id}/delete", [CurriculumController::class, "admin_destroy_topic"])->name("topic.destroy");

						Route::get("/{curriculum_topic_id}", [CurriculumController::class, "admin_topic_details"])->name("topic.details");

						Route::get("/{curriculum_topic_id}/create", [CurriculumController::class, "admin_create_material"])->name("material.create");
						Route::post("/{curriculum_topic_id}/create", [CurriculumController::class, "admin_store_material"])->name("material.store");

						Route::get("/{curriculum_topic_id}/{curriculum_material_id}/edit", [CurriculumController::class, "admin_edit_material"])->name("material.edit");
						Route::post("/{curriculum_topic_id}/{curriculum_material_id}/edit", [CurriculumController::class, "admin_update_material"])->name("material.update");

						Route::get("/{curriculum_topic_id}/{curriculum_material_id}/delete", [CurriculumController::class, "admin_delete_material"])->name("material.delete");
						Route::post("/{curriculum_topic_id}/{curriculum_material_id}/delete", [CurriculumController::class, "admin_destroy_material"])->name("material.destroy");
					}
				);
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
				Route::post('/{student_id}/{course_id}/max-session-update', [StudentController::class, 'admin_update_max_session'])->name('max-session.update');

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

				// Make student status is not imported again
				Route::get("{student_id}/normalize", [CourseStudentController::class, "normalize_confirmation"])->name("normalize.confirmation");
				Route::post("{student_id}/normalize", [CourseStudentController::class, "normalize_proceed"])->name("normalize.proceed");

				// Import from excel
				Route::get("/import-excel-student", [ExcelImportController::class, "import_excel_student_index"])->name("import-excel");
				Route::post("/import-excel-student", [ExcelImportController::class, "import_excel_student_store"])->name("import-excel.store");
			}
		);

		// Manage accounts
		Route::prefix('/account')
			->name('account.')
			->group(function () {

				// List of available accounts (categorized to enabled/disabled)
				Route::get('/', [AdminAccountController::class, 'index'])->name('index');

				// Account details
				Route::get('/{user_id}', [AdminAccountController::class, 'show_acc'])->name('show')->whereNumber('user_id');

				// Create new account
				Route::get('/create', [RegisteredUserController::class, 'admin_create'])->name('create');
				Route::post('/', [RegisteredUserController::class, 'admin_store'])->name('store');

				// Edit account data
				Route::get("/{user_id}/edit", [AdminAccountController::class, "edit_acc"])->name("acc_edit");
				Route::patch("/{user_id}/edit", [AdminAccountController::class, "update_acc"])->name("acc_edit.store");

				// Delete account data
				Route::get("/{user_id}/delete", [AdminAccountController::class, "delete"])->name("acc_delete");
				Route::delete("/{user_id}/delete", [AdminAccountController::class, "destroy"])->name("destroy");

				// Enable/disable account
				Route::get("/{user_id}/disable", [AdminAccountController::class, "disable_conf"])->name("acc_disable.conf");
				Route::post("/{user_id}/disable", [AdminAccountController::class, "disable_acc"])->name("acc_disable");
				Route::get("/{user_id}/enable", [AdminAccountController::class, "enable_conf"])->name("acc_enable.conf");
				Route::post("/{user_id}/enable", [AdminAccountController::class, "enable_acc"])->name("acc_enable");

				// Reset password
				Route::get("/{user_id}/reset-password", [AdminAccountController::class, "reset_password"])->name("reset-password");
				Route::post("/{user_id}/reset-password", [AdminAccountController::class, "reset_password_proceed"])->name("reset-password.proceed");
			}
		);
	});
