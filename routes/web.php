<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SysAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CustomOperationController;
use App\Http\Controllers\PushNotificationController;

// Verify email application (dont move this)
Auth::routes(['verify' => true]);

// For maintenance
Route::get("/sysadmin/login", [SysAdminController::class, "sysadmin_login"])->name("sysadmin.login");
Route::post("/sysadmin/login", [SysAdminController::class, "sysadmin_authenticate"])->name("sysadmin.authenticate");
Route::post("/sysadmin/logout", [SysAdminController::class, "sysadmin_logout"])->name("sysadmin.logout");

// Route::get('/custom-operation', [CustomOperationController::class, 'generate_progress_for_all_student']);
// Route::get('/custom-operation', [CustomOperationController::class, 'sync_syllabus_for_all_course']);
// Route::get('/custom-operation', [CustomOperationController::class, 'custom_operation']);


// Main routes
Route::middleware([])->group(function(){
	// Home
	Route::get('/', function () {
		if (Auth::check()) {
			// User is logged in, so redirect to a specific route
			return redirect()->route('dashboard');
		}
		return view('roles.guest.home');
	})->name('home');

	Route::middleware(["auth", "verified", 'remind_user'])->group(function(){
		Route::get('/dashboard', [DashboardController::class, "index"])->middleware(['auth', 'verified'])->name('dashboard');

		// Profile & notifications
		Route::prefix("/profile")->name("profile.")->middleware(["auth", "verified"])->group(function(){
			Route::get("/", [UserAccountController::class, "show"])->name("show");
			Route::post("/", [UserAccountController::class, "update"])->name("update");
		});

		Route::prefix("/notification")->name("notification.")->middleware(["auth", "verified"])->group(function(){
			Route::post("/{notification_id}", [NotificationController::class, "mark_as_read"])->name("mark-read")->whereNumber("notification_id");
			Route::post("/mark-read-all", [NotificationController::class, "mark_all_as_read"])->name("mark-all-read");
			Route::post("/{notification_id}/dismiss}", [NotificationController::class, "dismiss"])->name("dismiss")->whereNumber("notification_id");
			Route::post("/dismiss-all", [NotificationController::class, "dismiss_all"])->name("dismiss-all");
		});

		// Announcements
		Route::get("/announcement", [AnnouncementController::class, "all_list_announcement"])->name("list-announcement");
		Route::get("/announcement/{announcement_id}", [AnnouncementController::class, "all_view_announcement"])->name("view-announcement")->whereNumber("announcement_id");
	});

	// Authentication and registrations
	require __DIR__ . '/auth.php';

	// Role based routes
	require __DIR__ . '/roles/admin.php';
	require __DIR__ . '/roles/teacher.php';
	require __DIR__ . '/roles/student.php';
	require __DIR__ . '/roles/guest.php';

	// Push notification (postponed, VAPID keys-nya mabok)
	// Route::post('/save-subscription', [PushNotificationController::class, "saveSubscription"])->name("pushnotification.savesubscription");

	// Route::get("/test-notif", function(){
	// 	$pnc = new PushNotificationController();
	// 	$pnc->sendPushNotification();
	// });

	// Requests from Outside of LMS
	Route::prefix('/api')->group(function(){
		Route::post('/delete-image', function(Request $request){
			// return response()->json(['success' => true, 'path' => $request->path]);

			try {
				$status = Storage::disk('public')->delete($request->path);

				return response()->json(['success' => true, 'deletion_status' => $status, 'path' => $request->path], 200);
			}
			catch(Exception $e){
				return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
			}
		});
	});
});
