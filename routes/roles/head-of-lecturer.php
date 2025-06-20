<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::prefix('/head-of-lecturer')->name('head-of-lecturer.')->middleware('auth', 'role:head_of_lecturer', 'verified', "acc_not_disabled",)->group(function(){
    // Landing page
    Route::get('/', function () {
        return redirect(route('dashboard'));
    });

    // Courses information
    Route::prefix('/course')->name('course.')->group(function(){
        Route::get('/', [CourseController::class, 'head_of_lecturer_index'])->name('index');
        Route::get('/{course}', [CourseController::class, 'head_of_lecturer_show'])->name('show');
    });

    // Attendance
    Route::prefix('/attendance')->name('attendance.')->group(function(){
        Route::get('/', [AttendanceController::class, 'head_of_lecturer_index'])->name('index');
        Route::get('/{course_id}', [AttendanceController::class, 'head_of_lecturer_show'])->name('show')->whereNumber('course_id');
    });

    // Portfolio
    Route::prefix('/portfolio')->name('portfolio.')->group(function(){
        Route::get('/', [PortfolioController::class, 'head_of_lecturer_index'])->name('index');
        Route::get('/{course_id}', [PortfolioController::class, 'head_of_lecturer_show'])->name('show')->whereNumber('course_id');
        Route::delete('/{portfolio_id}/delete', [PortfolioController::class, 'head_of_lecturer_destroy'])->name('destroy')->whereNumber('portfolio_id');

        Route::post('/{portfolio_id}/highlight', [PortfolioController::class, 'head_of_lecturer_highlight'])->name('highlight')->whereNumber('portfolio_id');
        Route::post('/{portfolio_id}/unhighlight', [PortfolioController::class, 'head_of_lecturer_unhighlight'])->name('unhighlight')->whereNumber('portfolio_id');
    });
});