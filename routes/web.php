<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\UserController;

// Route untuk Login & Logout (Area Publik)
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/students/import', [StudentController::class, 'importData'])->name('students.import');


// Semua Route di dalam grup ini WAJIB LOGIN (Area Tertutup)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Semua Master Data
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('classes', ClassroomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::get('/riwayat-data', [LogActivityController::class, 'index']);
    Route::resource('users', UserController::class);
    Route::resource('schedules', App\Http\Controllers\ScheduleController::class);
});
