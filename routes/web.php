<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Auth\Events\Login;
use App\Models\Counselor;

// Catch-all route to ensure React handles the routing
Route::get('/', function () {
    return view('app');
});
Route::get('/admin', function () {
    return view('admin.login');
});
//login route
Route::get('login', [LoginController::class, 'show'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('dashboard', function () { return view('admin.dashboard'); })->middleware('auth')->name('dashboard');
Route::get('analytics', function(){ return view('admin.analytics'); })->middleware('auth')->name('analytics');
Route::get('/counsillors', function () { $counselors = Counselor::with('user')->get(); return view('admin.counsillor_directory', compact('counselors'));})->name('counsillors.index')->middleware(['web', 'auth']);
Route::post('/counselors/store', [CounselorController::class, 'store'])->name('counselors.store');
Route::get('/counsillors', [CounselorController::class, 'index'])->name('counsillors.index')->middleware('auth');
Route::get('/counsillor_log', [CounselorController::class, 'assignmentLogs'])->name('counsillor_log');
Route::patch('/counselors/{id}/toggle-status', [CounselorController::class, 'toggleStatus'])->name('counselors.toggle-status');
Route::put('/counselors/{id}', [CounselorController::class, 'update'])->name('counselors.update');
Route::delete('/counselors/{id}', [CounselorController::class, 'destroy'])->name('counselors.destroy');

//Users Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
    // Status Toggling Switch Engine Route Configuration
    Route::patch('/admin/users/{id}/toggle', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('admin.users.toggle');
});
Route::middleware(['auth'])->group(function () {

    // Universal Account Settings Core Pages
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
