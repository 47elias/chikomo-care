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
Route::get('/counsillors', function () {
    // Fetch all counselors from the database with their linked user details
    $counselors = Counselor::with('user')->get();
    // Pass the variable to the view
    return view('admin.counsillor_directory', compact('counselors'));
})->name('counsillors.index')->middleware(['web', 'auth']);
Route::get('/counsillors', [CounselorController::class, 'index'])->name('counsillors.index')->middleware('auth');
Route::get('counsillor_log', function() { return view('admin.counsillor_log'); })->middleware('auth')->name('counsillor_log');
