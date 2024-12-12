<?php

use App\Models\CourseTeacher;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DownloadResourceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LearningOutcomeController;
use App\Models\LearningOutcome;

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
				Route::get("/{course_id}/batch-assign", [CourseStudentController::class, "batch_assign"])->name("batch-assign")->whereNumber('course_id');
				Route::post("/{course_id}/batch-assign", [CourseStudentController::class, "batch_assign_store"])->name("batch-assign.store")->whereNumber('course_id');

				// Batch import old student data
				Route::get("/{course_id}/import-data", [CourseStudentController::class, "import_student_data"])->name("import-student-data")->whereNumber('course_id');
				Route::post("/{course_id}/import-data", [CourseStudentController::class, "import_student_data_store"])->name("import-student-data.store")->whereNumber('course_id');

				// Edit course data
				Route::get('/{course_id}/edit', [CourseController::class, 'admin_edit'])->name('edit')->whereNumber('course_id');
				Route::patch('/{course_id}', [CourseController::class, 'admin_update'])->name('update')->whereNumber('course_id');

				// Delete course
				Route::get("/{course_id}/delete", [CourseController::class, "admin_delete"])->name("delete")->whereNumber('course_id');
				Route::delete('/{course_id}/delete', [CourseController::class, 'admin_destroy'])->name('destroy')->whereNumber('course_id');

				// Manage LO
				Route::post("/{course_id}/learning-outcome/create", [LearningOutcomeController::class, "admin_store_lo"])->name("learning-outcome.store")->whereNumber("course_id");
				Route::post("/{course_id}/{learning_outcome_id}/learning-outcome/edit", [LearningOutcomeController::class, "admin_update_lo"])->name("learning-outcome.update")->whereNumber("course_id");
				Route::post("/{course_id}/{learning_outcome_id}/learning-outcome/delete", [LearningOutcomeController::class, "admin_destroy_lo"])->name("learning-outcome.destroy")->whereNumber("course_id");

				// Manage curriculum
					Route::prefix('/{course_id}/curriculum')
					->name('curriculum.')
					->group(function () {
						// Import from excel
						Route::get("/import-excel", [ExcelImportController::class, "import_excel_curriculum_index"])->name("import-excel");
						Route::post("/import-excel", [ExcelImportController::class, "import_excel_curriculum_store"])->name("import-excel.store");

						// Download import excel template
						Route::get("/import-excel/download-template", [DownloadResourceController::class, "topics_and_activities_import_excel_template"])->name("import-excel.download");

						// Add new curriculum topic
						Route::post("/create", [CurriculumController::class, "admin_store_topic"])->name("topic.store");

						// Modify curriculum topic
						Route::post("/{curriculum_topic_id}/edit", [CurriculumController::class, "admin_update_topic"])->name("topic.update")->whereNumber('curriculum_topic_id');

						// Delete curriculum topic
						Route::post("/{curriculum_topic_id}/delete", [CurriculumController::class, "admin_destroy_topic"])->name("topic.destroy")->whereNumber('curriculum_topic_id');

						// Show curriculum topic's detail
						Route::get("/{curriculum_topic_id}", [CurriculumController::class, "admin_topic_details"])->name("topic.details")->whereNumber('curriculum_topic_id');

						// Add new curriculum activity
						Route::get("/{curriculum_topic_id}/create", [CurriculumController::class, "admin_create_activity"])->name("activity.create")->whereNumber('curriculum_topic_id');
						Route::post("/{curriculum_topic_id}/create", [CurriculumController::class, "admin_store_activity"])->name("activity.store")->whereNumber('curriculum_topic_id');

						// Modify curriculum activity
						Route::get("/{curriculum_topic_id}/{curriculum_activity_id}/edit", [CurriculumController::class, "admin_edit_activity"])->name("activity.edit")->whereNumber(['curriculum_topic_id', 'curriculum_activity_id']);
						Route::post("/{curriculum_topic_id}/{curriculum_activity_id}/edit", [CurriculumController::class, "admin_update_activity"])->name("activity.update")->whereNumber(['curriculum_topic_id', 'curriculum_activity_id']);

						// Remove curriculum activity from curriculum topic
						Route::get("/{curriculum_topic_id}/{curriculum_activity_id}/delete", [CurriculumController::class, "admin_delete_activity"])->name("activity.delete")->whereNumber(['curriculum_topic_id', 'curriculum_activity_id']);
						Route::post("/{curriculum_topic_id}/{curriculum_activity_id}/delete", [CurriculumController::class, "admin_destroy_activity"])->name("activity.destroy")->whereNumber(['curriculum_topic_id', 'curriculum_activity_id']);
					}
				)->whereNumber("course_id");
			}
		);

		// Manage teachers
		Route::prefix('/teacher')
			->name('teacher.')
			->group(function () {

				// Active teachers list
				Route::get('/', [TeacherController::class, 'index'])->name('index');

				// Teacher details
				Route::get('/{teacher_id}', [TeacherController::class, 'show'])->name('show')
					->whereNumber('teacher_id');

				// Edit teacher data
				Route::get('/{teacher_id}/edit', [TeacherController::class, 'admin_edit'])->name('edit')->whereNumber('teacher_id');
				Route::patch('/{teacher_id}', [TeacherController::class, 'admin_update'])->name('update')->whereNumber('teacher_id');

				// Assign/unassign teacher to/from a course
				Route::get("/{teacher_id}/assign", [CourseTeacherController::class, "show"])->name("assign")->whereNumber('teacher_id');
				Route::post("/{teacher_id}/assign", [CourseTeacherController::class, "assign"])->name("assign.store")->whereNumber('teacher_id');

				Route::get('{teacher_id}/unassign/{course_id}', [CourseTeacherController::class, 'delete'])->name('unassign.delete')->whereNumber(['teacher_id', 'course_id']);
				Route::delete("/{teacher_id}/unassign/{course_id}", [CourseTeacherController::class, "unassign"])->name("unassign.destroy")->whereNumber(['teacher_id', 'course_id']);

			}
		);

		// Manage Students
		Route::prefix('/student')
			->name('student.')
			->group(function () {

				// List of active students
				Route::get('/', [StudentController::class, 'admin_index'])->name('index');
				Route::get('/{student_id}', [StudentController::class, 'admin_show'])->name('show')->whereNumber('student_id');
				Route::post('/{student_id}/{course_id}/max-session-update', [StudentController::class, 'admin_update_max_session'])->name('max-session.update')->whereNumber(['student_id', 'course_id']);

				// Edit student data
				Route::get('/{student_id}/edit', [StudentController::class, 'admin_edit'])->name('edit')->whereNumber('student_id');
				Route::patch('/{student_id}', [StudentController::class, 'admin_update'])->name('update')->whereNumber('student_id');

				// Assign/unassign students to/from a course
				Route::get('/{student_id}/assign', [CourseStudentController::class, 'create'])->name('assign.create')->whereNumber('student_id');
				Route::post('/{student_id}/assign', [CourseStudentController::class, 'store'])->name('assign.store')->whereNumber('student_id');

				Route::get('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'delete'])->name('unassign.delete')->whereNumber(['student_id', 'course_id']);
				Route::delete('{student_id}/unassign/{course_id}', [CourseStudentController::class, 'destroy'])->name('unassign.destroy')->whereNumber(['student_id', 'course_id']);

				// Assignment and attendance details
				Route::get("{student_id}/{course_id}/assignments", [AssignmentController::class, "admin_show"])->name("asg-details")->whereNumber(['student_id', 'course_id']);
				Route::get("{student_id}/{course_id}/attendance", [AttendanceController::class, "admin_show"])->name("atd-details")->whereNumber(['student_id', 'course_id']);

				// Make student status is not imported again
				Route::get("{student_id}/normalize", [CourseStudentController::class, "normalize_confirmation"])->name("normalize.confirmation")->whereNumber('student_id');
				Route::post("{student_id}/normalize", [CourseStudentController::class, "normalize_proceed"])->name("normalize.proceed")->whereNumber('student_id');

				// Import from excel
				Route::get("/import-excel", [ExcelImportController::class, "import_excel_student_index"])->name("import-excel");
				Route::post("/import-excel", [ExcelImportController::class, "import_excel_student_store"])->name("import-excel.store");

				// Download import excel template
				Route::get("/import-excel/download-template", [DownloadResourceController::class, "student_import_excel_template"])->name("import-excel.download");
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
				Route::get("/{user_id}/edit", [AdminAccountController::class, "edit_acc"])->name("acc_edit")->whereNumber('user_id');
				Route::patch("/{user_id}/edit", [AdminAccountController::class, "update_acc"])->name("acc_edit.store")->whereNumber('user_id');

				// Delete account data
				Route::get("/{user_id}/delete", [AdminAccountController::class, "delete"])->name("acc_delete")->whereNumber('user_id');
				Route::delete("/{user_id}/delete", [AdminAccountController::class, "destroy"])->name("destroy")->whereNumber('user_id');

				// Enable/disable account
				Route::get("/{user_id}/disable", [AdminAccountController::class, "disable_conf"])->name("acc_disable.conf")->whereNumber('user_id');
				Route::post("/{user_id}/disable", [AdminAccountController::class, "disable_acc"])->name("acc_disable")->whereNumber('user_id');
				Route::get("/{user_id}/enable", [AdminAccountController::class, "enable_conf"])->name("acc_enable.conf")->whereNumber('user_id');
				Route::post("/{user_id}/enable", [AdminAccountController::class, "enable_acc"])->name("acc_enable")->whereNumber('user_id');

				// Reset password
				Route::get("/{user_id}/reset-password", [AdminAccountController::class, "reset_password"])->name("reset-password")->whereNumber('user_id');
				Route::post("/{user_id}/reset-password", [AdminAccountController::class, "reset_password_proceed"])->name("reset-password.proceed")->whereNumber('user_id');
			}
		);

		// Manage announcement
		Route::prefix("/announcement")->name("announcement.")->group(function(){
			Route::get("/", [AnnouncementController::class, "index"])->name("index");

			Route::get("/create", [AnnouncementController::class, "create"])->name("create");
			Route::post("/create", [AnnouncementController::class, "store"])->name("store");

			Route::get("/{announcement_id}/edit", [AnnouncementController::class, "edit"])->name("edit");
			Route::post("/{announcement_id}/edit", [AnnouncementController::class, "update"])->name("update");

			Route::get("/{announcement_id}/delete", [AnnouncementController::class, "delete"])->name("delete");
			Route::post("/{announcement_id}/destroy", [AnnouncementController::class, "destroy"])->name("destroy");

			Route::get("/{announcement_id}", [AnnouncementController::class, "all_view_announcement"])->name("view-announcement")->whereNumber("announcement_id");
		});
	}
);
