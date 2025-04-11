<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SysAdminController;
use App\Http\Controllers\CustomOperationController;

Route::prefix('/sysadmin')->name('sysadmin.')->group(function(){
    // For maintenance
    Route::get("/login", [SysAdminController::class, "sysadmin_login"])->name("login");
    Route::post("/login", [SysAdminController::class, "sysadmin_authenticate"])->name("authenticate");

    Route::middleware('role:System Admin')->group(function(){
        Route::post("/logout", [SysAdminController::class, "sysadmin_logout"])->name("logout");

        // Custom operation
        // Route::get('/custom-operation', [CustomOperationController::class, 'generate_progress_for_all_student']);
        // Route::get('/custom-operation', [CustomOperationController::class, 'sync_syllabus_for_all_course']);
        // Route::get('/custom-operation', [CustomOperationController::class, 'custom_operation']);
    });
});
