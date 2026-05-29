<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnonymousController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PeerStoryController;
use App\Http\Controllers\CounselorPortalController;
use App\Http\Controllers\StressModuleController;
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

    // Profile management routes
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['web', 'auth'])->group(function () {
    // FIX: Replaced the route closure callback with an explicit link to your Controller class method
    Route::get('/anonymous', [AnonymousController::class, 'index'])->name('anonymous.index');
});
Route::middleware(['web'])->group(function () {

    // UPDATED ROUTE: Connected directly to AnalyticsController to feed real table data to your graphs
   Route::get('analytics', [AnalyticsController::class, 'index'])
    ->middleware('auth')
    ->name('analytics');
});
//Stress Modules Routes
Route::middleware(['web', 'auth'])->group(function () {
    // Stress Modules Application Routing Matrix
    Route::get('/stress-modules', [StressModuleController::class, 'index'])->name('stress-modules.index');
    Route::post('/stress-modules', [StressModuleController::class, 'store'])->name('stress-modules.store');
    Route::delete('/stress-modules/{id}', [StressModuleController::class, 'destroy'])->name('stress-modules.destroy');
});

// Peer Stories Routes
Route::middleware(['web', 'auth'])->group(function () {
    // Peer Narrative Interaction Routes Stack
    Route::get('/peer-stories', [PeerStoryController::class, 'index'])->name('peer-stories.index');
    Route::patch('/peer-stories/{id}/toggle', [PeerStoryController::class, 'toggleApproval'])->name('peer-stories.toggle');
    Route::delete('/peer-stories/{id}', [PeerStoryController::class, 'destroy'])->name('peer-stories.destroy');
});

// Counselor Portal Routes
Route::middleware(['web', 'auth'])->group(function () {
    // Counselor Module Application Interfacing Endpoints
    Route::get('/counselor-portal', [CounselorPortalController::class, 'index'])->name('counselor-portal.index');
    Route::post('/counselor-portal/accept/{id}', [CounselorPortalController::class, 'acceptRequest'])->name('counselor.accept');
    Route::get('/counselor-portal/chat/{id}', [CounselorPortalController::class, 'liveChatRoom'])->name('counselor.chat');
    // Terminate, save metrics summaries, and log session data
    Route::post('/counselor-portal/close/{id}', [CounselorPortalController::class, 'closeSession'])->name('counselor.close');
});
