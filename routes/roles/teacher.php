<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CurriculumController;
use Illuminate\Http\Request;

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
				Route::post("/{course_id}/synchronize", [CurriculumController::class, "teacher_synchronize"])->name("synchronize");
			}
		);

		Route::prefix("/topic")
			->name("topic.")
			->group(function(){
				// Route::get('/', function(){
				// 	return redirect(route("teacher.mycourse.index"));
				// })->name("index");

				// Add new topic
				Route::get("/{course_id}", [TopicController::class, "teacher_create"])->name("create");
				Route::post("/{course_id}", [TopicController::class, "teacher_store"])->name("store");

				// Topic details
				Route::get("/{course_id}/{topic_id}/detail", [TopicController::class, "teacher_show"])->name("show");

				// Edit topic
				Route::get("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_edit"])->name("edit");
				Route::patch("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_update"])->name("update");

				// Delete topic
				Route::get("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_delete"])->name("delete");
				Route::delete("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_destroy"])->name("destroy");

			}
		);

		Route::prefix('/material')
			->name('material.')
			->group(function () {
				// Route::get('/', function(){
				// 	return redirect(route("teacher.mycourse.index"));
				// })->name("index");

				// Add new material
				Route::get('/upload/{topic_id}', [MaterialController::class, 'teacher_create'])->name('upload')->whereNumber('course_id');
				Route::post('/upload/{topic_id}', [MaterialController::class, 'teacher_store'])->name('store')->whereNumber('course_id');

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
				Route::get('/{student_id}/progress/{course_id}', [ProgressController::class, 'index'])
					->name('show.progress')
					->whereNumber('student_id')
					->whereNumber('course_id');

				// Update student's material progress
				Route::patch('/progress/{course_id}/{student_id}', [ProgressController::class, 'update'])
					->name('update.progress')
					->whereNumber('progress_id');

			}
		);

		// Attendance
		Route::prefix('/attendance')
			->name('attendance.')
			->group(function () {

				// Pick an intended course to manage attendance
				Route::get("/", [AttendanceController::class, "index"])->name("index");

				// List of attendance data in the selected course
				Route::get('/{course_id}', [AttendanceController::class, 'show'])->name('show');

				// Upload new attendance data
				Route::get('/{course_id}/upload', [AttendanceController::class, 'create'])->name('upload')->whereNumber('course_id');
				Route::post('/{course_id}/upload', [AttendanceController::class, 'store'])->name('store');

				// Edit attendance data
				Route::get("/{attendance_data_id}/edit", [AttendanceController::class, "edit"])->name("edit");
				Route::post("/{attendance_data_id}/edit", [AttendanceController::class, "update"])->name("update");

			}
		);

		// Assignment
		Route::prefix("/assignment")
			->name("assignment.")
			->group(function(){

				// Pick an intended course to manage assignment
				Route::get("/", [AssignmentController::class, "teacher_index"])->name("index");

				// List of assignment data in the selected course
				Route::get("/{course_id}", [AssignmentController::class, "teacher_show"])->name("show");

				// Upload new assignment
				Route::get("/upload/{course_id}", [AssignmentController::class, "teacher_upload"])->name("upload");
				Route::post("/upload/{course_id}", [AssignmentController::class, "teacher_store"])->name("store");

				// Edit assignment
				Route::get("/{assignment_id}/edit", [AssignmentController::class, "teacher_edit"])->name("edit");
				Route::patch("/{assignment_id}/edit", [AssignmentController::class, "teacher_update"])->name("update");

				// Delete assignment
				Route::get("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_delete"])->name("delete");
				Route::delete("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_destroy"])->name("destroy");

				// Check assignment submissions
				Route::get("/{assignment_id}/submission", [AssignmentController::class, "teacher_check_submission"])->name("check");

				// Check assignment submissions history for the selected students and add/check feedback to submissions
				Route::get("/{assignment_id}/{student_id}/history", [AssignmentController::class, "teacher_check_history"])->name("submission-history");
				Route::post("/{submission_id}/{student_id}/history", [AssignmentController::class, "teacher_feedback"])->name("feedback");

			}
		);

	}
);
