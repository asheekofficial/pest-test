<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::get('/', [CourseController::class, 'index'])->name('home');

Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('course-details');

Route::middleware(['auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class)->except('show');
    Route::get('video-details/{video}', [DashboardController::class, 'show'])->name('video-details');

    Route::post('/logout', function () {})->name('logout');
});

require __DIR__.'/auth.php';
