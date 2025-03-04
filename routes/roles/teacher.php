<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GoogleServiceController;
use App\Http\Controllers\DownloadResourceController;

Route::prefix('/teacher')
	->name('teacher.')
	->middleware(['auth', 'role:Teacher', 'verified', "acc_not_disabled", 'remind_user'])
	->group(function () {

		// ===== DASHBOARD ===== //
		Route::get('/', function () {
			return redirect(route('dashboard'));
		});


		Route::prefix('/my-course')->name('mycourse.')->group(function () {
			// ===== MANAGE COURSES ===== //
			// List of assigned courses
			Route::get('/', [CourseController::class, 'teacher_index'])->name('index');

			// Course details
			Route::get('/{course_id}', [CourseController::class, 'teacher_show'])->name('show')->whereNumber('course_id');

			// Synchronize with curriculum
			Route::post("/{course_id}/synchronize", [CurriculumController::class, "teacher_synchronize"])->name("synchronize")->whereNumber('course_id');

			// Import from excel
			Route::get("/{course_id}/import-excel", [ExcelImportController::class, "import_excel_topics_and_activities_index"])->name("import-excel-topicandactivities")->whereNumber("course_id");
			Route::post("/{course_id}/import-excel", [ExcelImportController::class, "import_excel_topics_and_activities_store"])->name("import-excel-topicandactivities.store")->whereNumber("course_id");

			// Download import excel template
			Route::get("/import-excel/download-template", [DownloadResourceController::class, "topics_and_activities_import_excel_template"])->name("import-excel.download");

			// Pick activities from syllabus/curriculum
			Route::get("/{course_id}/pick-course", [CurriculumController::class, "teacher_pick_course"])->name("pick-course")->whereNumber("course_id");
			Route::post("/{course_id}/pick-course", [CurriculumController::class, "teacher_save_picked_course"])->name("save-picked-course")->whereNumber("course_id");


			// ===== TOPICS ===== //
			Route::prefix("/topic")
				->name("topic.")
				->group(function(){
					// Add new topic
					Route::post("/{course_id}", [TopicController::class, "teacher_store"])->name("store")->whereNumber('course_id');

					// Topic details
					Route::get("/{course_id}/{topic_id}/detail", [TopicController::class, "teacher_show"])->name("show")->whereNumber(['course_id', 'topic_id']);

					// Edit topic
					Route::patch("/{course_id}/{topic_id}/edit", [TopicController::class, "teacher_update"])->name("update")->whereNumber(['course_id', 'topic_id']);

					// Delete topic
					Route::delete("/{course_id}/{topic_id}/delete", [TopicController::class, "teacher_destroy"])->name("destroy")->whereNumber(['course_id', 'topic_id']);
				}
			);

			// ===== ACTIVITIES ===== //
			Route::prefix('/activity')
				->name('activity.')
				->group(function () {
					// Add new activity
					Route::get('/upload/{topic_id}', [ActivityController::class, 'teacher_create'])->name('upload')->whereNumber('topic_id');
					Route::post('/upload/{topic_id}', [ActivityController::class, 'teacher_store'])->name('store')->whereNumber('topic_id');

					// Edit activity
					Route::get('/{activity_id}/edit', [ActivityController::class, 'teacher_edit'])->name('edit')->whereNumber('activity_id');
					Route::patch('/{activity_id}', [ActivityController::class, 'teacher_update'])->name('update')->whereNumber('activity_id');

					// Delete activity
					Route::delete('/{activity_id}', [ActivityController::class, 'teacher_destroy'])->name('destroy')->whereNumber('activity_id');

				}
			);
		});


		// ===== MANAGE STUDENTS ===== //
		Route::prefix('/student')
			->name('student.')
			->group(function () {

				// Pick an intended course where the intended student is enrolled
				Route::get('/', [StudentController::class, 'teacher_select_course'])->name('select-course');

				// Pick an intended student to manage
				Route::get('/{course_id}', [StudentController::class, 'teacher_select_student'])->name('select-student')->whereNumber('course_id');

				// Student information (activity progress and meeting link)
				Route::get('/{student_id}/progress/{course_id}', [StudentController::class, 'teacher_index'])->name('show')->whereNumber(['student_id', 'course_id']);

				// Update student's activity progress
				Route::patch('/progress/{course_id}/{student_id}/activity-access', [StudentController::class, 'teacher_update_activity_access'])->name('update.progress.activity-access')->whereNumber(['course_id', 'student_id']);

				// Update student's meeting link
				Route::patch('/progress/{course_id}/{student_id}/meeting-link', [StudentController::class, 'teacher_update_meeting_link'])->name('update.progress.meeting-link')->whereNumber(['course_id', 'student_id']);

				// Upload portfolio for student
				Route::post('/{student_id}/{course_id}/upload-portfolio', [StudentController::class, 'teacher_store_portfolio'])->name('store.portfolio')->whereNumber(['course_id', 'student_id']);

				// Delete a portfolio image of a student
				Route::delete('/portfolio/{portfolio_id}/delete', [StudentController::class, 'teacher_destroy_portfolio'])->name('destroy.portfolio')->whereNumber('portfolio_id');
			}
		);


		// ===== ATTENDANCE ===== //
		Route::prefix('/attendance')
			->name('attendance.')
			->group(function () {
				// Pick an intended course to manage attendance
				Route::get("/", [AttendanceController::class, "index"])->name("index");

				// List of attendance data in the selected course
				Route::get('/{course_id}', [AttendanceController::class, 'show'])->name('show')->whereNumber('course_id');

				// Upload new attendance data
				Route::get("/{course_id}/preupload", [AttendanceController::class, "select_students"])->name("select-students")->whereNumber("course_id");
				Route::post("/{course_id}/preupload", [AttendanceController::class, "submit_and_proceed"])->name("submit-and-proceed")->whereNumber("course_id");
				Route::get('/{course_id}/upload', [AttendanceController::class, 'create'])->name('upload')->whereNumber('course_id');
				Route::post('/{course_id}/upload', [AttendanceController::class, 'store'])->name('store')->whereNumber('course_id');

				// Edit attendance data
				// Route::get("/{attendance_data_id}/edit", [AttendanceController::class, "edit"])->name("edit")->whereNumber('attendance_data_id');
				// Route::post("/{attendance_data_id}/edit", [AttendanceController::class, "update"])->name("update")->whereNumber('attendance_data_id');

				// Self attendance
				Route::get("/{course_id}/self/check-in", [TeacherController::class, "check_in"])->name("check-in")->whereNumber("course_id");
				Route::post("/{course_id}/self/check-in", [TeacherController::class, "check_in_store"])->name("check-in.store")->whereNumber("course_id");
				Route::post("/{course_id}/self/check-out", [TeacherController::class, "check_out_store"])->name("check-out.store")->whereNumber("course_id");
			}
		);


		// ===== ASSIGNMENT ===== //
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

		// ===== FORUM DISCUSSION ===== //
		Route::get('/forum', [ForumController::class, 'index_teacher'])->name('forum.index')->whereNumber('course_id');
		Route::get('/forum/{course_id}/retrieve', [ForumController::class, 'retrieve_message_teacher'])->name('forum.retrieve')->whereNumber('course_id');
		Route::post('/forum/{course_id}/send', [ForumController::class, 'send_message_teacher'])->name('forum.send')->whereNumber('course_id');

		// ===== VIEW ANNOUNCEMENT ===== //
		Route::get("/announcement/{announcement_id}", [AnnouncementController::class, "all_view_announcement"])->name("view-announcement")->whereNumber("announcement_id");

		// ===== GOOGLE ===== //
		Route::get('/google/redirect', [GoogleServiceController::class, 'redirectToGoogle'])->name('google.redirect');
		Route::get('/google/callback', [GoogleServiceController::class, 'handleGoogleCallback']);
		Route::get('google/logout', [GoogleServiceController::class, 'logout'])->name('google.logout');
	}
);
