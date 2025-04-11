<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::prefix('/head-of-lecturer')->name('head-of-lecturer.')->middleware('auth', 'role:head_of_lecturer', 'verified', "acc_not_disabled",)->group(function(){
    // Landing page
    Route::get('/', function () {
        return redirect(route('dashboard'));
    });

    // Portfolio
    Route::prefix('/portfolio')->name('portfolio.')->group(function(){
        Route::get('/', [PortfolioController::class, 'head_of_lecturer_index'])->name('index');
        Route::get('/{course_id}', [PortfolioController::class, 'head_of_lecturer_show'])->name('show')->whereNumber('course_id');
        Route::delete('/{portfolio_id}/delete', [PortfolioController::class, 'head_of_lecturer_destroy'])->name('destroy')->whereNumber('portfolio_id');
    });
});