<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CounselorController;
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
