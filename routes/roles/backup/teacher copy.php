<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialProgressController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TopicController;
use App\Models\MaterialProgress;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')
	->name('teacher.')
	->middleware(['auth', 'role:Teacher', 'verified', "acc_not_disabled"])
	->group(function () {
		Route::get('/', function () {
			return redirect(route('dashboard'));
		});

		Route::prefix('/mycourse') // DONE
			->name('mycourse.')
			->group(function () {
				// Course Controller
				Route::get('/', [CourseController::class, 'teacher_index'])->name('index'); // DONE
				Route::get('/{course_id}', [CourseController::class, 'teacher_show'])->name('show')->whereNumber('course_id'); // DONE
				Route::get('/{course_id}/progress', [MaterialProgressController::class, 'students_progress'])->name('progress');
			});

		Route::prefix('/material') // ON HALT
			->name('material.')
			->group(function () {
				// Material Controller
				Route::get('/upload/{topic_id}', [MaterialController::class, 'teacher_create'])->name('upload')->whereNumber('course_id'); // DONE
				Route::post('/upload/{topic_id}', [MaterialController::class, 'teacher_store'])->name('store')->whereNumber('course_id'); // DONE

				Route::get('/{material_id}/edit', [MaterialController::class, 'teacher_edit'])->name('edit')->whereNumber('material_id'); // DONE
				Route::patch('/{material_id}', [MaterialController::class, 'teacher_update'])->name('update')->whereNumber('material_id'); // DONE

				Route::get('/{material_id}/delete', [MaterialController::class, 'teacher_delete'])->name('remove')->whereNumber('material_id');
				Route::delete('/{material_id}', [MaterialController::class, 'teacher_destroy'])->name('destroy')->whereNumber('material_id');
			});

		Route::prefix("/topic")
			->name("topic.")
			->group(function(){
				Route::get("/{course_id}", [TopicController::class, "teacher_create"])->name("create");
				Route::post("/{course_id}", [TopicController::class, "teacher_store"])->name("store");
				Route::get("/{course_id}/{topic_id}/detail", [TopicController::class, "teacher_show"])->name("show");
				Route::get("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_edit"])->name("edit");
				Route::patch("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_update"])->name("update");
				Route::get("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_delete"])->name("delete");
				Route::delete("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_destroy"])->name("destroy");
			});

		Route::prefix('/student') // DONE
			->name('student.')
			->group(function () {
				// Student Controller
				Route::get('/', [StudentController::class, 'teacher_select_course'])->name('select-course'); // DONE
				Route::get('/{course_id}', [StudentController::class, 'teacher_select_student'])->name('select-student')->whereNumber('course_id'); // DONE

				// Progress Controller
				Route::get('/{student_id}/progress/{course_id}', [MaterialProgressController::class, 'index']) // DONE
					->name('show.progress')
					->whereNumber('student_id')
					->whereNumber('course_id'); // Show progression table // DONE
				Route::patch('/progress/{progress_id}', [MaterialProgressController::class, 'update']) // DONE
					->name('update.progress')
					->whereNumber('progress_id');
			});

		Route::prefix('/attendance') // ON HALT
			->name('attendance.')
			->group(function () {

				// Teacher yang check attendance
				Route::get("/", [AttendanceController::class, "index"])->name("index");
				Route::get('/{course_id}', [AttendanceController::class, 'show'])->name('show');
				Route::get('/{course_id}/upload', [AttendanceController::class, 'create'])->name('upload')->whereNumber('course_id');
				Route::post('/{course_id}/upload', [AttendanceController::class, 'store'])->name('store');
				Route::get("/{attendance_data_id}/edit", [AttendanceController::class, "edit"])->name("edit");
				Route::post("/{attendance_data_id}/edit", [AttendanceController::class, "update"])->name("update");

				// Schedule Controller
/* 				Route::get('/')->name('index'); // Show all schedule for the teacher
				Route::get('/{attendance_id}')->name('show.student')->whereNumber('attendance_id');

				Route::prefix('/verify')
					->name('vefiry.')
					->group(function () {
						// Attendance Controller (student_attendance)
						Route::get('/')->name('index'); // Show a list of student teached by this teacher
						Route::get('/{student_id}')->name('show'); // Show student attendance table

						Route::get('/{student_id}/validate')->name('validate')->whereNumber('student_id');
						Route::patch('/{student_id}')->name('update')->whereNumber('student_id');
					}); */
			});

		Route::prefix("/assignment")
			->name("assignment.")
			->group(function(){
				Route::get("/", [AssignmentController::class, "teacher_index"])->name("index");
				Route::get("/{course_id}", [AssignmentController::class, "teacher_show"])->name("show");
				Route::get("/upload/{course_id}", [AssignmentController::class, "teacher_upload"])->name("upload");
				Route::post("/upload/{course_id}", [AssignmentController::class, "teacher_store"])->name("store");
				Route::get("/{assignment_id}/edit", [AssignmentController::class, "teacher_edit"])->name("edit");
				Route::patch("/{assignment_id}/edit", [AssignmentController::class, "teacher_update"])->name("update");
				Route::get("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_delete"])->name("delete");
				Route::delete("/{assignment_id}/delete-confirm", [AssignmentController::class, "teacher_destroy"])->name("destroy");

				Route::get("/{assignment_id}/submission", [AssignmentController::class, "teacher_check_submission"])->name("check");
				Route::get("/{submission_id}/{student_id}/history", [AssignmentController::class, "teacher_check_history"])->name("submission-history");
				Route::post("/{submission_id}/{student_id}/history", [AssignmentController::class, "teacher_feedback"])->name("feedback");
			});

/* 		Route::prefix('/schedule') // ON HALT
			->name('schedule.')
			->group(function () {
				// Schedule Controller
				Route::get('/', [ScheduleController::class, 'teacher_index'])->name('index'); // Show all schedule for the teacher

			}); */
	});
