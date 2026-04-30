<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Auth\Events\Login;

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
Route::get('counsillors',function(){return view('admin.counsillors');})->middleware('auth')->name('counsillors');
