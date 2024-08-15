<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\DownloadResourceController;

Route::prefix('/teacher')
	->name('teacher.')
	->middleware(['auth', 'role:Teacher', 'verified', "acc_not_disabled"])
	->group(function () {

		// Landing page
		Route::get('/', function () {
			return redirect(route('dashboard'));
		});

		// Manage courses, topics, and materials
		Route::prefix('/my-course')
			->name('mycourse.')
			->group(function () {

				// List of assigned courses
				Route::get('/', [CourseController::class, 'teacher_index'])->name('index');

				// Course details
				Route::get('/{course_id}', [CourseController::class, 'teacher_show'])->name('show')->whereNumber('course_id');

				// Synchronize with curriculum
				Route::post("/{course_id}/synchronize", [CurriculumController::class, "teacher_synchronize"])->name("synchronize")->whereNumber('course_id');

				// Import from excel
				Route::get("/{course_id}/import-excel", [ExcelImportController::class, "import_excel_topics_and_materials_index"])->name("import-excel-topicsandmaterials");
				Route::post("/{course_id}/import-excel", [ExcelImportController::class, "import_excel_topics_and_materials_store"])->name("import-excel-topicsandmaterials.store");

				// Download import excel template
				Route::get("/import-excel/download-template", [DownloadResourceController::class, "topics_and_materials_import_excel_template"])->name("import-excel.download");
			}
		);

		Route::prefix("/topic")
			->name("topic.")
			->group(function(){
				// Route::get('/', function(){
				// 	return redirect(route("teacher.mycourse.index"));
				// })->name("index");

				// Add new topic
				Route::get("/{course_id}", [TopicController::class, "teacher_create"])->name("create")->whereNumber('course_id');
				Route::post("/{course_id}", [TopicController::class, "teacher_store"])->name("store")->whereNumber('course_id');

				// Topic details
				Route::get("/{course_id}/{topic_id}/detail", [TopicController::class, "teacher_show"])->name("show")->whereNumber(['course_id', 'topic_id']);

				// Edit topic
				Route::get("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_edit"])->name("edit")->whereNumber(['course_id', 'topic_id']);
				Route::patch("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_update"])->name("update")->whereNumber(['course_id', 'topic_id']);

				// Delete topic
				Route::get("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_delete"])->name("delete")->whereNumber(['course_id', 'topic_id']);
				Route::delete("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_destroy"])->name("destroy")->whereNumber(['course_id', 'topic_id']);
			}
		);

		Route::prefix('/material')
			->name('material.')
			->group(function () {
				// Route::get('/', function(){
				// 	return redirect(route("teacher.mycourse.index"));
				// })->name("index");

				// Add new material
				Route::get('/upload/{topic_id}', [MaterialController::class, 'teacher_create'])->name('upload')->whereNumber('topic_id');
				Route::post('/upload/{topic_id}', [MaterialController::class, 'teacher_store'])->name('store')->whereNumber('topic_id');

				// Edit material
				Route::get('/{material_id}/edit', [MaterialController::class, 'teacher_edit'])->name('edit')->whereNumber('material_id');
				Route::patch('/{material_id}', [MaterialController::class, 'teacher_update'])->name('update')->whereNumber('material_id');

				// Delete material
				Route::get('/{material_id}/delete', [MaterialController::class, 'teacher_delete'])->name('remove')->whereNumber('material_id');
				Route::delete('/{material_id}', [MaterialController::class, 'teacher_destroy'])->name('destroy')->whereNumber('material_id');

			}
		);

		// Manage students
		Route::prefix('/student')
			->name('student.')
			->group(function () {

				// Pick an intended course where the intended student is enrolled
				Route::get('/', [StudentController::class, 'teacher_select_course'])->name('select-course');

				// Pick an intended student to manage
				Route::get('/{course_id}', [StudentController::class, 'teacher_select_student'])->name('select-student')->whereNumber('course_id');

				// List of student's material progress
				Route::get('/{student_id}/progress/{course_id}', [ProgressController::class, 'index'])->name('show.progress')->whereNumber(['student_id', 'course_id']);

				// Update student's material progress
				Route::patch('/progress/{course_id}/{student_id}', [ProgressController::class, 'update'])->name('update.progress')->whereNumber(['course_id', 'student_id']);
			}
		);

		// Attendance
		Route::prefix('/attendance')
			->name('attendance.')
			->group(function () {

				// Pick an intended course to manage attendance
				Route::get("/", [AttendanceController::class, "index"])->name("index");

				// List of attendance data in the selected course
				Route::get('/{course_id}', [AttendanceController::class, 'show'])->name('show')->whereNumber('course_id');

				// Upload new attendance data
				Route::get('/{course_id}/upload', [AttendanceController::class, 'create'])->name('upload')->whereNumber('course_id');
				Route::post('/{course_id}/upload', [AttendanceController::class, 'store'])->name('store')->whereNumber('course_id');

				// Edit attendance data
				Route::get("/{attendance_data_id}/edit", [AttendanceController::class, "edit"])->name("edit")->whereNumber('attendance_data_id');
				Route::post("/{attendance_data_id}/edit", [AttendanceController::class, "update"])->name("update")->whereNumber('attendance_data_id');

			}
		);

		// Assignment
		Route::prefix("/assignment")
			->name("assignment.")
			->group(function(){

				// Pick an intended course to manage assignment
				Route::get("/", [AssignmentController::class, "teacher_index"])->name("index");

				// List of assignment data in the selected course
				Route::get("/{course_id}", [AssignmentController::class, "teacher_show"])->name("show")->whereNumber('course_id');

				// Upload new assignment
				Route::get("/upload/{course_id}", [AssignmentController::class, "teacher_upload"])->name("upload")->whereNumber('course_id');
				Route::post("/upload/{course_id}", [AssignmentController::class, "teacher_store"])->name("store")->whereNumber('course_id');

				// Edit assignment
				Route::get("/{assignment_id}/edit", [AssignmentController::class, "teacher_edit"])->name("edit")->whereNumber('assignment_id');
				Route::patch("/{assignment_id}/edit", [AssignmentController::class, "teacher_update"])->name("update")->whereNumber('assignment_id');

				// Delete assignment
				Route::get("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_delete"])->name("delete")->whereNumber('assignment_id');
				Route::delete("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_destroy"])->name("destroy")->whereNumber('assignment_id');

				// Check assignment submissions
				Route::get("/{assignment_id}/submission", [AssignmentController::class, "teacher_check_submission"])->name("check")->whereNumber('assignment_id');

				// Check assignment submissions history for the selected students and add/check feedback to submissions
				Route::get("/{assignment_id}/{student_id}/history", [AssignmentController::class, "teacher_check_history"])->name("submission-history")->whereNumber(['assignment_id', 'student_id']);
				Route::post("/{submission_id}/{student_id}/history", [AssignmentController::class, "teacher_feedback"])->name("feedback")->whereNumber(['submission_id', 'student_id']);

			}
		);

	}
);
