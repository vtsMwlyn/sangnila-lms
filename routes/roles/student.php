<?php

use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CourseStudentController;
use App\Models\CourseStudent;

Route::prefix('/student')
	->name('student.')
	->middleware(['auth', 'role:Student', 'verified', "acc_not_disabled", 'remind_user'])
	->group(function() {

		// Landing page
		Route::get('/', function() {
			return redirect(route('dashboard'));
		});

		// Ceritanya bayar
		// Route::get("/payment", [PaymentController::class, "student_pay"])->name("pay");
		// Route::post("/payment", [PaymentController::class, "student_pay_proceed"])->name("pay.proceed");

		// My courses
		Route::prefix('/my-course')
			->name('mycourse.')
			->group(function(){

				// List of enrolled courses
				Route::get('/', [CourseController::class, 'student_index'])->name('index');

				// Course details and available topics and activities
				Route::get('/{course_id}', [CourseController::class, 'student_show'])->name('show')->whereNumber('course_id');

				Route::get("/{activity_id}/preview", [ActivityController::class, "preview"])->name("preview")->whereNumber("activity_id");

				// Self attendance (check in/out)
				Route::get('/{course_id}/check-in', [StudentController::class, "student_check_in"])->name('check-in')->whereNumber('course_id');
				Route::post('/{course_id}/check-in', [StudentController::class, "student_check_in_store"])->name('check-in.store')->whereNumber('course_id');
				Route::post('/{course_id}/check-out', [StudentController::class, "student_check_out_store"])->name('check-out.store')->whereNumber('course_id');
			}
		);

		// Assignment
		Route::prefix('/assignment')
			->name('assignment.')
			->group(function() {

				// Pick an intended course to show assignment
				Route::get('/', [AssignmentController::class, "student_index"])->name('index');

				// List of assigned assignments in the selected course
				Route::get("/{course_id}", [AssignmentController::class, "student_show"])->name("show");

				// Upload assignment
				Route::post("/{course_id}/{assignment_id}/submit", [AssignmentController::class, "student_store"])->name("store");
			}
		);

		// Attendance
		Route::prefix('/attendance')
			->name('attendance.')
			->group(function() {

				// Pick an intended course to show attendance data
				Route::get('/', [AttendanceController::class, "student_index"])->name('index');

				// List of attendance data in the selected course
				Route::get('/{course_id}', [AttendanceController::class, "student_show"])->name('show');
			}
		);

		// Learning Documentation
		Route::prefix('/learning-documentation')
			->name('learning-documentation.')
			->group(function() {

				// List of enrolled courses
				Route::get('/', [StudentController::class, 'student_index'])->name('index');

				// Show portfolio and certificate
				Route::get('/{course_id}', [StudentController::class, 'student_show'])->name('show')->whereNumber('course_id');

				// Upload portfolio
				Route::post('{course_id}/upload-portfolio', [StudentController::class, 'student_store_portfolio'])->name('store.portfolio')->whereNumber('course_id');

				// View certificate
				Route::get('/certificate/{course_id}/{student_id}', [CourseStudentController::class, 'generate_certificate'])->name('view-certificate');
			}
		);

		// Forum Discussion
		Route::prefix('/forum')
			->name('forum.')
			->group(function() {

				// Show Messages
				Route::get('/', [ForumController::class, 'index_student'])->name('index')->whereNumber('course_id');

				// Get Latest Messages
				Route::get('/{course_id}/retrieve', [ForumController::class, 'retrieve_message_student'])->name('retrieve')->whereNumber('course_id');

				// Send Messages
				Route::post('/{course_id}/send', [ForumController::class, 'send_message_student'])->name('send')->whereNumber('course_id');
		});
	}
);
